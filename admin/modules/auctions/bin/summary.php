<?php
$path = $_SERVER[ 'DOCUMENT_ROOT' ];

require_once( $path . "/admin/modules/auctions/src/auction.class.php" );
require_once( $path . "/admin/functions/forms.php" );

$db = new database( $pdo );
$auction = new auction( $pdo );

$list = $auction->getAllAuctions('productCode', 'ASC');
?>

<div id="menuList" class="list-group">
  <?php
  foreach ( $list as $row => $link ) {
    ?>
  <div class="row g-2 pb-2 mt-1" id="row<?php echo $link[ 'auctionId' ]; ?>" data-id="<?php echo $link[ 'auctionId' ]; ?>" style="background-color: #F1F1F1;">
      <?php 
      echo input("hidden", $link[ 'auctionHash' ], $link[ 'auctionHash' ]); 
      $productName = $auction->productName( $link['productId']);
      $productCode = $auction->productCode( $link['productId']);
      $HighestBid = $auction->getHighestBidForLot( $link['productId']);
      ?>
 
      <div class="col-1"><?php echo $productCode; ?></div>
      <div class="col-4 mr-1"><?php echo $productName; ?></div>
      <div class="col-2"><?php echo $link['startDate']; ?> <?php echo $link['startTime']; ?></div>
      <div class="col-2"><?php echo $link['endDate']; ?> <?php echo $link['endTime']; ?></div>
      <div class="col-1"><?php echo $HighestBid; ?></div>
    
      
          <div class="col-1 text-center">
         <div class="form-check-inline form-switch mt-2">
        <input name"active" class="form-check-input switchbox" type="checkbox" data-set="<?php echo $link['auctionHash']; ?>" <?php echo ($link['auctionActive'] == 'y') ? 'checked' : ''; ?>>
      </div>
      </div>
        <div class="col-1 text-end">
        <a href="/admin/auctions/edit/<?php echo $link['auctionHash']; ?>/" class="btn btn-dark btn-sm edit-btn mt-1"><i class="bi bi-pencil"></i></a>
      <button value="<?php echo $link['auctionId']; ?>" 
			 data-message="Weet je zeker dat je deze veiling wilt verwijderen?" 
			 id="btn<?php echo $link['auctionId']; ?>"
			 class="btn btn-danger btn-sm btn-ok mt-1" 
			 data-bs-toggle="modal" 
			 data-bs-target="#dialogModal"> <i class="bi bi-trash" data-set="<?php echo $link['auctionId']; ?>"></i> 
    </button>
      </div>
    </div>
<?php } ?>
</div>
<!-- Modal -->
<div class="modal fade" id="dialogModal" tabindex="-1" aria-labelledby="dialogModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dialogModalLabel">Let op!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-center dialogmessage"></p>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="RowId" value="" id="RowId">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
        <button type="button" class="btn btn-danger btn-delete btn-ok" data-bs-dismiss="modal">Verwijderen</button>
      </div>
    </div>
  </div>
</div>
