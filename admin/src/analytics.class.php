<?php
class Analytics {
    
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    private function getCurrentSiteId() {
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
            // Check if sites table exists
            $stmt = $this->pdo->prepare("SHOW TABLES LIKE 'sites'");
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                return [['id' => 1, 'domain' => 'default', 'name' => 'Default Site']];
            }
            
            $stmt = $this->pdo->prepare("SELECT id, domain, name, description, status FROM sites WHERE status = 'active' ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll();
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
            
            // Voer beide queries uit
            $stmt = $this->pdo->prepare($currentQuery);
            $stmt->execute($startDate && $endDate ? [$siteId, $startDate, $endDate] : [$siteId]);
            $currentResult = $stmt->fetch();
            
            $stmt = $this->pdo->prepare($aggregatedQuery);
            $stmt->execute($startDate && $endDate ? [$siteId, $startDate, $endDate] : [$siteId]);
            $aggregatedResult = $stmt->fetch();
            
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
    
    public function getEnhancedStats($startDate = null, $endDate = null) {
        try {
            $basicStats = $this->getStats($startDate, $endDate);
            
            $enhancedStats = array_merge($basicStats, [
                'uniqueVisitors' => $this->getUniqueVisitors($startDate, $endDate),
                'bounceRate' => $this->getBounceRate($startDate, $endDate),
                'avgPagesPerSession' => $this->getAvgPagesPerSession($startDate, $endDate),
                'topReferrers' => $this->getTopReferrers(5, $startDate, $endDate),
                'deviceBreakdown' => $this->getDeviceBreakdown($startDate, $endDate),
                'browserBreakdown' => $this->getBrowserBreakdown($startDate, $endDate),
                'topPages' => $this->getTopPages(10, $startDate, $endDate),
                'conversionRate' => $this->getConversionRate($startDate, $endDate)
            ]);
            
            return $enhancedStats;
        } catch (Exception $e) {
            error_log("Error in getEnhancedStats: " . $e->getMessage());
            // Return basic stats with empty arrays for complex data
            return array_merge($this->getStats($startDate, $endDate), [
                'uniqueVisitors' => 0,
                'bounceRate' => 0,
                'avgPagesPerSession' => 0,
                'topReferrers' => [],
                'deviceBreakdown' => [],
                'browserBreakdown' => [],
                'topPages' => [],
                'conversionRate' => 0
            ]);
        }
    }
    
    public function getUniqueVisitors($startDate = null, $endDate = null) {
        try {
            $whereClause = "WHERE page_url NOT LIKE '%?e=%' 
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
            
            $stmt = $this->pdo->prepare("SELECT COUNT(DISTINCT ip_address) as unique_visitors FROM analytics " . $whereClause);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (int)($result['unique_visitors'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getUniqueVisitors: " . $e->getMessage());
            return 0;
        }
    }

    public function getBounceRate($startDate = null, $endDate = null) {
        try {
            $whereClause = "WHERE page_url NOT LIKE '%?e=%' 
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
            
            $stmt = $this->pdo->prepare("
                SELECT 
                    ROUND((SUM(bounced) * 100.0 / COUNT(*)), 2) as bounce_rate 
                FROM analytics " . $whereClause
            );
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (float)($result['bounce_rate'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getBounceRate: " . $e->getMessage());
            return 0;
        }
    }

    public function getAvgPagesPerSession($startDate = null, $endDate = null) {
        try {
            $whereClause = "WHERE page_views > 0 
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
            
            $stmt = $this->pdo->prepare("
                SELECT ROUND(AVG(page_views), 2) as avg_pages 
                FROM analytics 
                " . $whereClause
            );
            $stmt->execute($params);
            $result = $stmt->fetch();
            return (float)($result['avg_pages'] ?? 0);
        } catch (Exception $e) {
            error_log("Error in getAvgPagesPerSession: " . $e->getMessage());
            return 0;
        }
    }

    public function getTopReferrers($limit = 5, $startDate = null, $endDate = null) {
        $whereClause = "WHERE referer_url != 'unknown' 
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

    public function getDeviceBreakdown($startDate = null, $endDate = null) {
        try {
            $whereClause = "WHERE page_url NOT LIKE '%?e=%' 
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
            
            // Eerst controleren of er data is
            $checkStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM analytics " . $whereClause);
            $checkStmt->execute($params);
            $totalRecords = $checkStmt->fetch()['total'];
            
            if ($totalRecords == 0) {
                // Geen data, return dummy data
                return [
                    ['device' => 'Desktop', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Mobile', 'count' => 0, 'percentage' => 0],
                    ['device' => 'Tablet', 'count' => 0, 'percentage' => 0]
                ];
            }
            
            // Verbeterde device detection met user agent parsing
            $stmt = $this->pdo->prepare("
                SELECT 
                    CASE 
                        WHEN is_mobile = 1 AND (
                            user_agent LIKE '%iPad%' OR 
                            user_agent LIKE '%Android%' AND user_agent LIKE '%Tablet%' OR
                            user_agent LIKE '%PlayBook%' OR
                            user_agent LIKE '%Silk%' OR
                            user_agent LIKE '%Kindle%'
                        ) THEN 'Tablet'
                        WHEN is_mobile = 1 THEN 'Mobile'
                        ELSE 'Desktop'
                    END as device,
                    COUNT(*) as count,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics " . $whereClause . ")), 2) as percentage
                FROM analytics " . $whereClause . "
                GROUP BY device
                ORDER BY count DESC
            ");
            $stmt->execute($params);
            
            $result = $stmt->fetchAll();
            
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

    public function getBrowserBreakdown($startDate = null, $endDate = null) {
        try {
            $whereClause = "WHERE browser != 'unknown' 
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
            
            // Eerst controleren of er data is
            $checkStmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM analytics " . ($startDate && $endDate ? "WHERE DATE(visit_time) BETWEEN ? AND ?" : ""));
            $checkStmt->execute($startDate && $endDate ? [$startDate, $endDate] : []);
            $totalRecords = $checkStmt->fetch()['total'];
            
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
                ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM analytics " . ($startDate && $endDate ? "WHERE DATE(visit_time) BETWEEN ? AND ?" : "") . ")), 2) as percentage
                FROM analytics 
                " . $whereClause . "
                GROUP BY browser 
                ORDER BY count DESC
                LIMIT 5
            ");
            $stmt->execute($params);
            
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

    public function getTopPages($limit = 10, $startDate = null, $endDate = null) {
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
            
            $params[] = $limit; // Add limit parameter at the end
            
            // Simplified query without complex subqueries
            $stmt = $this->pdo->prepare("
                SELECT 
                    page_url, 
                    COUNT(*) as count,
                    ROUND((COUNT(*) * 100.0 / (
                        SELECT COUNT(*) FROM analytics " . ($startDate && $endDate ? "WHERE DATE(visit_time) BETWEEN ? AND ?" : "") . "
                    )), 2) as percentage
                FROM analytics 
                " . $whereClause . "
                GROUP BY page_url 
                ORDER BY count DESC 
                LIMIT ?
            ");
            
            // Execute with all parameters
            if ($startDate && $endDate) {
                $stmt->execute([$startDate, $endDate, $startDate, $endDate, $limit]);
            } else {
                $stmt->execute([$limit]);
            }
            
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