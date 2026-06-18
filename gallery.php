    
 
 <?php
 include 'inc/head.php';
 
 ?>
 
 
 <div class="wrapper">
 
 
  <?php
 include 'inc/header.php';
 
 ?>
 
 

				

	<div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-caption">
                            <h1 class="page-title">Gallery</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.page-header-->
        <!-- tour-service -->
        <div class="content">
            <div class="container">
                <div class="row">
				
				 <?php
					include('inc/config.php'); 

					$sql = "SELECT * FROM tbltourpackages "; 
					$query = $dbh->prepare($sql);
					$query->execute();
					$results = $query->fetchAll(PDO::FETCH_OBJ);

					foreach($results as $row) {
					?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 mb20">
					<div class="gallery-img">
						<a href="admin/pacakgeimages/<?php echo htmlentities($row->PackageImage); ?>" class="image-link imghover" title="gallery zoom image">
							<img src="admin/pacakgeimages/<?php echo htmlentities($row->PackageImage); ?>" alt="Package Image" class="img-fluid">
						</a>
					</div>
				</div>
                   
					<?php } ?>
				   </div>
            </div>
        </div>
		
		
		
		
   <?php
   include 'inc/footer.php';
   
   ?>
    