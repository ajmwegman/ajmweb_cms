<?php
class Analytics {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getCurrentSiteId() {
        try {
            // Check if sites table exists
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE 'sites'");
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                return 1; // Default site_id
            }
            
            // Get current domain
            $currentDomain = $_SERVER['HTTP_HOST'] ?? 'default';
            
            // Get site_id for domain
            $stmt = $this->pdo->prepare("SELECT id FROM sites WHERE domain = ? AND status = 'active'");
            $stmt->execute([$currentDomain]);
            $result = $stmt->fetch();
            
            if ($result) {
                return $result['id'];
            }
            
            // If domain not found, return default site_id
            return 1;
        } catch (Exception $e) {
            error_log("Error getting current site_id: " . $e->getMessage());
            return 1; // Default site_id
        }
    }
    
    public function getAllSites() {
        try {
            // Check if config table exists (same source used by navbar)
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE 'config'");
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return [['id' => 1, 'domain' => 'default', 'name' => 'Default Site']];
            }

            // Use config table to match site dropdown in navigation
            $stmt = $this->pdo->prepare("SELECT id, loc_website AS domain, web_naam AS name FROM config ORDER BY web_naam");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting all sites: " . $e->getMessage());
            return [['id' => 1, 'domain' => 'default', 'name' => 'Default Site']];
        }
    }

    public function getStats($startDate = null, $endDate = null, $siteId = null) {
        try {
            // Get site_id if not provided
            if ($siteId === null) {
                $siteId = $this->getCurrentSiteId();
            }
            
            // Combineer data uit beide tabellen
            $currentWhereClause = "WHERE site_id = ? 
                                 AND page_url NOT LIKE '%?e=%' 
                                 AND page_url NOT LIKE '%?channel=%' 
                                 AND page_url NOT LIKE '%?from=%' 
                                 AND page_url NOT LIKE '%?utm_%' 
                                 AND page_url NOT LIKE '%?fbclid=%' 
                                 AND page_url NOT LIKE '%?gclid=%' 
                                 AND LENGTH(page_url) < 200";
            $aggregatedWhereClause = "WHERE site_id = ? 
                                    AND page_url NOT LIKE '%?e=%' 
                                    AND page_url NOT LIKE '%?channel=%' 
                                    AND page_url NOT LIKE '%?from=%' 
                                    AND page_url NOT LIKE '%?utm_%' 
                                    AND page_url NOT LIKE '%?fbclid=%' 
                                    AND page_url NOT LIKE '%?gclid=%' 
                                    AND LENGTH(page_url) < 200";
            $params = [$siteId];
            
            if ($startDate && $endDate) {
                $currentWhereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
                $aggregatedWhereClause .= " AND date_key BETWEEN ? AND ?";
                $params = array_merge($params, [$startDate, $endDate, $startDate, $endDate]);
            }
            
            // Query voor huidige data (laatste 24 uur)
            $currentQuery = "SELECT 
                COUNT(*) as totalVisitors, 
                SUM(page_views) as totalPageViews, 
                AVG(session_duration) as averageDuration, 
                SUM(bounced) as totalBounces 
                FROM analytics " . $currentWhereClause;
            
            // Query voor geaggregeerde data (ouder dan 24 uur)
            $aggregatedQuery = "SELECT
                SUM(total_visits) as totalVisitors,
                SUM(total_page_views) as totalPageViews,
                AVG(total_session_duration) as averageDuration,
                SUM(total_bounces) as totalBounces
                FROM analytics_aggregated " . $aggregatedWhereClause;

            // Voer query voor huidige data uit
            $stmt = $this->pdo->prepare($currentQuery);
            $stmt->execute($startDate && $endDate ? [$siteId, $startDate, $endDate] : [$siteId]);
            $currentResult = $stmt->fetch();

            // Controleer of de geaggregeerde tabel bestaat
            $aggregatedResult = [
                'totalVisitors' => 0,
                'totalPageViews' => 0,
                'averageDuration' => 0,
                'totalBounces' => 0
            ];
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE 'analytics_aggregated'");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $stmt = $this->pdo->prepare($aggregatedQuery);
                $stmt->execute($startDate && $endDate ? [$siteId, $startDate, $endDate] : [$siteId]);
                $aggregatedResult = $stmt->fetch();
            }
            
            // Combineer resultaten
            $result = [
                'totalVisitors' => ($currentResult['totalVisitors'] ?? 0) + ($aggregatedResult['totalVisitors'] ?? 0),
                'totalPageViews' => ($currentResult['totalPageViews'] ?? 0) + ($aggregatedResult['totalPageViews'] ?? 0),
                'averageDuration' => ($currentResult['averageDuration'] ?? 0) + ($aggregatedResult['averageDuration'] ?? 0),
                'totalBounces' => ($currentResult['totalBounces'] ?? 0) + ($aggregatedResult['totalBounces'] ?? 0)
            ];
            
            // Ensure we have valid numeric values
            return [
                'totalVisitors' => (int)($result['totalVisitors'] ?? 0),
                'totalPageViews' => (int)($result['totalPageViews'] ?? 0),
                'averageDuration' => (float)($result['averageDuration'] ?? 0),
                'totalBounces' => (int)($result['totalBounces'] ?? 0)
            ];
        } catch (Exception $e) {
            error_log("Error in getStats: " . $e->getMessage());
            return [
                'totalVisitors' => 0,
                'totalPageViews' => 0,
                'averageDuration' => 0,
                'totalBounces' => 0
            ];
        }
    }
    
    public function getEnhancedStats($startDate = null, $endDate = null, $siteId = null) {
        try {
            $basicStats = $this->getStats($startDate, $endDate, $siteId);

            $enhancedStats = array_merge($basicStats, [
                'uniqueVisitors' => $this->getUniqueVisitors($startDate, $endDate, $siteId),
                'bounceRate' => $this->getBounceRate($startDate, $endDate, $siteId),
                'avgPagesPerSession' => $this->getAvgPagesPerSession($startDate, $endDate, $siteId),
                'topReferrers' => $this->getTopReferrers(5, $startDate, $endDate, $siteId),
                'topSearchKeywords' => $this->getTopSearchKeywords(10, $startDate, $endDate, $siteId),
                'deviceBreakdown' => $this->getDeviceBreakdown($startDate, $endDate, $siteId),
                'browserBreakdown' => $this->getBrowserBreakdown($startDate, $endDate, $siteId),
                'topPages' => $this->getTopPages(10, $startDate, $endDate, $siteId),
                'conversionRate' => $this->getConversionRate($startDate, $endDate)
            ]);

            return $enhancedStats;
        } catch (Exception $e) {
            error_log("Error in getEnhancedStats: " . $e->getMessage());
            // Return basic stats with empty arrays for complex data
            return array_merge($this->getStats($startDate, $endDate, $siteId), [
                'uniqueVisitors' => 0,
                'bounceRate' => 0,
                'avgPagesPerSession' => 0,
                'topReferrers' => [],
                'topSearchKeywords' => [],
                'deviceBreakdown' => [],
                'browserBreakdown' => [],
                'topPages' => [],
                'conversionRate' => 0
            ]);
        }
    }
    
    public function getUniqueVisitors($startDate = null, $endDate = null, $siteId = null) {
        try {
            if ($siteId === null) {
                $siteId = $this->getCurrentSiteId();
            }
            $whereClause = "WHERE site_id = ? AND page_url NOT LIKE '%?e=%' AND page_url NOT LIKE '%?channel=%' AND page_url NOT LIKE '%?from=%' AND page_url NOT LIKE '%?utm_%' AND page_url NOT LIKE '%?fbclid=%' AND page_url NOT LIKE '%?gclid=%' AND LENGTH(page_url) < 200";
            $params = [$siteId];

            if ($startDate && $endDate) {
                $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
            }

            $stmt = $this->pdo->prepare("SELECT COUNT(DISTINCT ip_address) as unique_visitors FROM analytics " . $whereClause);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (int)($result['unique_visitors'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getUniqueVisitors: " . $e->getMessage());
            return 0;
        }
    }

    public function getBounceRate($startDate = null, $endDate = null, $siteId = null) {
        try {
            $stats = $this->getStats($startDate, $endDate, $siteId);
            $totalVisits = $stats['totalVisitors'] ?? 0;
            $totalBounces = $stats['totalBounces'] ?? 0;
            return $totalVisits > 0 ? round(($totalBounces * 100.0) / $totalVisits, 2) : 0;
        } catch (Exception $e) {
            error_log("Error in getBounceRate: " . $e->getMessage());
            return 0;
        }
    }

    public function getAvgPagesPerSession($startDate = null, $endDate = null, $siteId = null) {
        try {
            if ($siteId === null) {
                $siteId = $this->getCurrentSiteId();
            }
            $whereClause = "WHERE site_id = ? AND page_views > 0 AND page_url NOT LIKE '%?e=%' AND page_url NOT LIKE '%?channel=%' AND page_url NOT LIKE '%?from=%' AND page_url NOT LIKE '%?utm_%' AND page_url NOT LIKE '%?fbclid=%' AND page_url NOT LIKE '%?gclid=%' AND LENGTH(page_url) < 200";
            $params = [$siteId];

            if ($startDate && $endDate) {
                $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
            }

            $stmt = $this->pdo->prepare("SELECT ROUND(AVG(page_views), 2) as avg_pages FROM analytics " . $whereClause);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (float)($result['avg_pages'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getAvgPagesPerSession: " . $e->getMessage());
            return 0;
        }
    }

    public function getTopReferrers($limit = 5, $startDate = null, $endDate = null, $siteId = null) {
        if ($siteId === null) {
            $siteId = $this->getCurrentSiteId();
        }
        $whereClause = "WHERE site_id = ? AND referer_url != 'unknown'
                       AND page_url NOT LIKE '%?e=%'
                       AND page_url NOT LIKE '%?channel=%'
                       AND page_url NOT LIKE '%?from=%'
                       AND page_url NOT LIKE '%?utm_%'
                       AND page_url NOT LIKE '%?fbclid=%'
                       AND page_url NOT LIKE '%?gclid=%'
                       AND LENGTH(page_url) < 200";
        $params = [$siteId];

        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        $params[] = $limit; // Add limit parameter at the end

        $stmt = $this->pdo->prepare("
            SELECT referer_url, COUNT(*) as count
            FROM analytics
            " . $whereClause . "
            GROUP BY referer_url
            ORDER BY count DESC
            LIMIT ?
        ");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTopSearchKeywords($limit = 10, $startDate = null, $endDate = null, $siteId = null) {
        if ($siteId === null) {
            $siteId = $this->getCurrentSiteId();
        }
        
        $whereClause = "WHERE site_id = ? AND referer_url IS NOT NULL AND referer_url != 'unknown' AND referer_url != ''";
        $params = [$siteId];

        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        $params[] = $limit;

        $stmt = $this->pdo->prepare("
            SELECT 
                CASE 
                    WHEN referer_url LIKE '%google.%' THEN 
                        CASE 
                            WHEN referer_url LIKE '%q=%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referer_url, 'q=', -1), '&', 1)
                            WHEN referer_url LIKE '%query=%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referer_url, 'query=', -1), '&', 1)
                            ELSE 'Google (direct)'
                        END
                    WHEN referer_url LIKE '%bing.%' THEN 
                        CASE 
                            WHEN referer_url LIKE '%q=%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referer_url, 'q=', -1), '&', 1)
                            ELSE 'Bing (direct)'
                        END
                    WHEN referer_url LIKE '%yahoo.%' THEN 
                        CASE 
                            WHEN referer_url LIKE '%p=%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referer_url, 'p=', -1), '&', 1)
                            ELSE 'Yahoo (direct)'
                        END
                    WHEN referer_url LIKE '%duckduckgo.%' THEN 
                        CASE 
                            WHEN referer_url LIKE '%q=%' THEN SUBSTRING_INDEX(SUBSTRING_INDEX(referer_url, 'q=', -1), '&', 1)
                            ELSE 'DuckDuckGo (direct)'
                        END
                    WHEN referer_url = '' OR referer_url = 'unknown' THEN 'Direct bezoek'
                    WHEN referer_url LIKE '%facebook.%' THEN 'Facebook'
                    WHEN referer_url LIKE '%twitter.%' OR referer_url LIKE '%t.co%' THEN 'Twitter'
                    WHEN referer_url LIKE '%instagram.%' THEN 'Instagram'
                    WHEN referer_url LIKE '%linkedin.%' THEN 'LinkedIn'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(REPLACE(referer_url, 'https://', ''), 'http://', ''), '/', 1), '?', 1)
                END as source,
                COUNT(*) as count,
                ROUND((COUNT(*) * 100.0 / (
                    SELECT COUNT(*) FROM analytics WHERE site_id = ? " . 
                    ($startDate && $endDate ? " AND DATE(visit_time) BETWEEN ? AND ?" : "") . "
                )), 2) as percentage
            FROM analytics 
            " . $whereClause . "
            GROUP BY source 
            HAVING source IS NOT NULL AND source != ''
            ORDER BY count DESC 
            LIMIT ?
        ");
        
        // Build parameters for subquery
        $executeParams = $params;
        if ($startDate && $endDate) {
            // Insert subquery params before the limit
            array_splice($executeParams, -1, 0, [$siteId, $startDate, $endDate]);
        } else {
            // Insert subquery param before the limit
            array_splice($executeParams, -1, 0, [$siteId]);
        }
        
        $stmt->execute($executeParams);
        $result = $stmt->fetchAll();
        
        // URL decode the search terms
        foreach ($result as &$row) {
            if (strpos($row['source'], '%') !== false) {
                $row['source'] = urldecode($row['source']);
            }
            // Clean up common URL artifacts
            $row['source'] = str_replace(['+', '%20'], ' ', $row['source']);
            $row['source'] = trim($row['source']);
        }
        
        return $result;
    }

    public function getDeviceBreakdown($startDate = null, $endDate = null, $siteId = null) {
        try {
            if ($siteId === null) {
                $siteId = $this->getCurrentSiteId();
            }
            
            $whereClause = "WHERE site_id = ?";
            $params = [$siteId];
            
            if ($startDate && $endDate) {
                $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
            }
            
            // Debug: Check what we're searching for
            error_log("getDeviceBreakdown - siteId: " . $siteId);
            error_log("getDeviceBreakdown - startDate: " . $startDate);
            error_log("getDeviceBreakdown - endDate: " . $endDate);
            error_log("getDeviceBreakdown - whereClause: " . $whereClause);
            error_log("getDeviceBreakdown - params: " . print_r($params, true));
            
            // Eerst controleren of er data is
            $checkStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM analytics " . $whereClause);
            $checkStmt->execute($params);
            $totalRecords = $checkStmt->fetch()['total'];
            
            error_log("getDeviceBreakdown - totalRecords found: " . $totalRecords);
            
            // Also check total records without filters to see if there's any data at all
            $totalCheckStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM analytics WHERE site_id = ?");
            $totalCheckStmt->execute([$siteId]);
            $totalAllRecords = $totalCheckStmt->fetch()['total'];
            
            error_log("getDeviceBreakdown - totalAllRecords for siteId: " . $totalAllRecords);
            
            if ($totalRecords == 0) {
                // Geen data, return dummy data
                error_log("getDeviceBreakdown - No records found, returning dummy data");
                return [
                    ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            // Simplified device detection based on user_agent
            $stmt = $this->pdo->prepare("
                SELECT 
                    CASE 
                        WHEN user_agent LIKE '%iPad%' 
                            OR user_agent LIKE '%Android%Tablet%' 
                            OR user_agent LIKE '%PlayBook%' 
                            OR user_agent LIKE '%Silk%' 
                            OR user_agent LIKE '%Kindle%' THEN 'Tablet'
                        WHEN user_agent LIKE '%Mobile%' 
                            OR user_agent LIKE '%Android%' 
                            OR user_agent LIKE '%iPhone%' 
                            OR user_agent LIKE '%BlackBerry%' 
                            OR user_agent LIKE '%Windows Phone%' THEN 'Mobile'
                        ELSE 'Desktop'
                    END as device,
                    COUNT(*) as count,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics " . $whereClause . ")), 2) as percentage
                FROM analytics " . $whereClause . "
                GROUP BY device
                ORDER BY count DESC
            ");
            // Execute with double params because subquery needs same parameters
            $doubleParams = array_merge($params, $params);
            error_log("getDeviceBreakdown - doubleParams: " . print_r($doubleParams, true));
            $stmt->execute($doubleParams);
            
            $result = $stmt->fetchAll();
            error_log("getDeviceBreakdown - result: " . print_r($result, true));
            
            // Als er geen resultaten zijn, voeg dummy data toe
            if (empty($result)) {
                return [
                    ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            // Zorg ervoor dat alle device types aanwezig zijn
            $devices = ['Desktop', 'Mobile', 'Tablet'];
            $existingDevices = array_column($result, 'device');
            
            foreach ($devices as $device) {
                if (!in_array($device, $existingDevices)) {
                    $result[] = ['device' => $device, 'count' => 0, 'percentage' => 0];
                }
            }
            
            // Sorteer op count (hoogste eerst)
            usort($result, function($a, $b) {
                return $b['count'] - $a['count'];
            });
            
            return $result;
        } catch (PDOException $e) {
            // Bij fout, return dummy data
            return [
                ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
            ];
        }
    }

    public function getBrowserBreakdown($startDate = null, $endDate = null, $siteId = null) {
        try {
            if ($siteId === null) {
                $siteId = $this->getCurrentSiteId();
            }
            
            $whereClause = "WHERE site_id = ? AND browser != 'unknown'";
            $params = [$siteId];
            
            if ($startDate && $endDate) {
                $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
            }
            
            // Debug: Check what we're searching for
            error_log("getBrowserBreakdown - siteId: " . $siteId);
            error_log("getBrowserBreakdown - startDate: " . $startDate);
            error_log("getBrowserBreakdown - endDate: " . $endDate);
            error_log("getBrowserBreakdown - whereClause: " . $whereClause);
            error_log("getBrowserBreakdown - params: " . print_r($params, true));
            
            // Eerst controleren of er data is
            $checkStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM analytics " . $whereClause);
            $checkStmt->execute($params);
            $totalRecords = $checkStmt->fetch()['total'];
            
            error_log("getBrowserBreakdown - totalRecords found: " . $totalRecords);
            
            if ($totalRecords == 0) {
                // Geen data, return dummy data
                return [
                    ['browser' => 'Chrome', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Firefox', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Safari', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            $stmt = $this->pdo->prepare("
                SELECT browser, COUNT(*) as count,
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics " . $whereClause . ")), 2) as percentage
                FROM analytics 
                " . $whereClause . "
                GROUP BY browser 
                ORDER BY count DESC
                LIMIT 5
            ");
            // Execute with double params because subquery needs same parameters
            $doubleParams = array_merge($params, $params);
            error_log("getBrowserBreakdown - doubleParams: " . print_r($doubleParams, true));
            $stmt->execute($doubleParams);
            
            $result = $stmt->fetchAll();
            
            // Als er geen resultaten zijn, voeg dummy data toe
            if (empty($result)) {
                return [
                    ['browser' => 'Chrome', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Firefox', 'count' => 0, 'percentage' => 0],
                    ['browser' => 'Safari', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            return $result;
        } catch (PDOException $e) {
            // Bij fout, return dummy data
            return [
                ['browser' => 'Chrome', 'count' => 0, 'percentage' => 0],
                ['browser' => 'Firefox', 'count' => 0, 'percentage' => 0],
                ['browser' => 'Safari', 'count' => 0, 'percentage' => 0]
            ];
        }
    }

    public function getTopPages($limit = 10, $startDate = null, $endDate = null, $siteId = null) {
        try {
            $whereClause = "WHERE page_url IS NOT NULL AND page_url != '' AND page_url != '/' 
                           AND page_url NOT LIKE '%?e=%' 
                           AND page_url NOT LIKE '%?channel=%' 
                           AND page_url NOT LIKE '%?from=%' 
                           AND page_url NOT LIKE '%?utm_%' 
                           AND page_url NOT LIKE '%?fbclid=%' 
                           AND page_url NOT LIKE '%?gclid=%' 
                           AND LENGTH(page_url) < 200";
            $params = [];
            
            if ($startDate && $endDate) {
                $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
                $params = [$startDate, $endDate];
            }
            
            if ($siteId) {
                $whereClause .= " AND site_id = ?";
                $params[] = $siteId;
            }
            
            $params[] = $limit; // Add limit parameter at the end
            
            // Build the subquery for percentage calculation
            $subqueryWhere = "";
            $subqueryParams = [];
            
            if ($startDate && $endDate) {
                $subqueryWhere .= "WHERE DATE(visit_time) BETWEEN ? AND ?";
                $subqueryParams = [$startDate, $endDate];
            }
            
            if ($siteId) {
                if ($subqueryWhere) {
                    $subqueryWhere .= " AND site_id = ?";
                } else {
                    $subqueryWhere = "WHERE site_id = ?";
                }
                $subqueryParams[] = $siteId;
            }
            
            // Simplified query without complex subqueries
            $stmt = $this->pdo->prepare("
                SELECT 
                    page_url, 
                    COUNT(*) as count,
                    ROUND((COUNT(*) * 100.0 / (
                        SELECT COUNT(*) FROM analytics " . $subqueryWhere . "
                    )), 2) as percentage
                FROM analytics 
                " . $whereClause . "
                GROUP BY page_url 
                ORDER BY count DESC 
                LIMIT ?
            ");
            
            // Build execution parameters: subquery params + main query params + limit
            $executeParams = array_merge($subqueryParams, $params);
            $stmt->execute($executeParams);
            
            $result = $stmt->fetchAll();
            
            // If no results, return empty array
            if (empty($result)) {
                return [];
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error in getTopPages: " . $e->getMessage());
            return [];
        }
    }

    public function getConversionRate($startDate = null, $endDate = null) {
        $whereClause = "WHERE session_duration > 300 
                       AND page_url NOT LIKE '%?e=%' 
                       AND page_url NOT LIKE '%?channel=%' 
                       AND page_url NOT LIKE '%?from=%' 
                       AND page_url NOT LIKE '%?utm_%' 
                       AND page_url NOT LIKE '%?fbclid=%' 
                       AND page_url NOT LIKE '%?gclid=%' 
                       AND LENGTH(page_url) < 200";
        $params = [];
        
        if ($startDate && $endDate) {
            $whereClause .= " AND DATE(visit_time) BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
        }
        
        // Build the query with proper parameter handling
        $subqueryWhere = "";
        $allParams = $params; // Start with main query params
        
        if ($startDate && $endDate) {
            $subqueryWhere = "WHERE DATE(visit_time) BETWEEN ? AND ?";
            $allParams = array_merge($allParams, [$startDate, $endDate]); // Add subquery params
        }
        
        $stmt = $this->pdo->prepare("
            SELECT 
                ROUND((COUNT(DISTINCT session_id) * 100.0 / 
                (SELECT COUNT(DISTINCT session_id) FROM analytics " . $subqueryWhere . ")), 2) as conversion_rate
            FROM analytics 
            " . $whereClause
        );
        $stmt->execute($allParams);
        return $stmt->fetch()['conversion_rate'];
    }
    
    public function getVisitorCountsByDay($startDate, $endDate) {
        $sql = "SELECT COUNT(*) as count, DATE(visit_time) as visit_date FROM analytics WHERE DATE(visit_time) BETWEEN :startDate AND :endDate GROUP BY DATE(visit_time) ORDER BY visit_date ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $visitorCounts = [];
            $visitDates = [];

            foreach ($result as $row) {
                $visitorCounts[] = $row['count'];
                $visitDates[] = $row['visit_date'];
            }

            return ['counts' => $visitorCounts, 'dates' => $visitDates];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    public function getEnhancedVisitorData($startDate, $endDate) {
        $sql = "SELECT 
                    DATE(visit_time) as visit_date,
                    COUNT(*) as visitors,
                    SUM(page_views) as page_views,
                    SUM(bounced) as bounces
                FROM analytics 
                WHERE DATE(visit_time) BETWEEN :startDate AND :endDate 
                GROUP BY DATE(visit_time) 
                ORDER BY visit_date ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dates = [];
            $visitors = [];
            $pageViews = [];
            $bounces = [];

            foreach ($result as $row) {
                $dates[] = $row['visit_date'];
                $visitors[] = (int)$row['visitors'];
                $pageViews[] = (int)$row['page_views'];
                $bounces[] = (int)$row['bounces'];
            }

            return [
                'dates' => $dates,
                'visitors' => $visitors,
                'pageViews' => $pageViews,
                'bounces' => $bounces
            ];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
?>