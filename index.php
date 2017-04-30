<?php
//akses fungsi
require_once('fungsi/fungsi.php');
konek_db();

$peringatan="";
if(isset($_POST['upload'])){
	$target_dir = "asset/citra/";
      $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
      $uploadOk = 1;
      $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
      $check = getimagesize($_FILES["gambar"]["tmp_name"]);
      if($check !== false) {
              $uploadOk = 1;
           
          } else {
              $peringatan="maaf yang anda pilih bukan gambar :-(";
              $uploadOk = 0;

          }
      

      if ($_FILES["gambar"]["size"] > 2000000) {
          $peringatan='ukuran gambar terlalu besar pilih gambar dengan ukuran kurang 2 MB';
          $uploadOk = 0;
         
      }

      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "JPG" && $imageFileType != "BMP" && $imageFileType != "bmp") {
          $peringatan='pilih gambar dengan format JPG, PNG, JPEG, atau BMP';
          $uploadOk = 0;
          
      }

      if ($uploadOk == 0) {

      } else {
          if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
              $nama_file=$_FILES['gambar']['name'];

          } else {
              echo "<script>alert('gambar tidak terupload ke server'); </script>";
              echo "<meta http-equiv='refresh' content='0; url=update_studio.php'>";
              
          }
      }

      if ($uploadOk == 1) {
      	# code...
      

      //ekstraksi ciri warna:
      	$nama_gambar = $target_dir.$nama_file;
      	$img = imagecreatefrombmp($nama_gambar);
     	$width = imagesx($img);
		$height = imagesy($img);

		$white=0;
		$black=0;
		$sum_pixel = $width * $height;

		for($x=0; $x<$width; $x++){
			for($y=0; $y<$height; $y++){

				//mengambil data RGB tiap piksel
				$rgb = imagecolorat($img, $x, $y);
				$warna = $rgb & 0xFF;

				//mengecek kondisi biner tiap pixel
				if ($warna==0) {
					$black++;
				}else{
					$white++;
				}
				
			}
		}

		$savetodb = mysql_query("INSERT INTO img_ext VALUES('','".$nama_file."','".$black."','".$white."','".$sum_pixel."');");


	}
}


 ?>

  <!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Image Processing</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">

<!--Icons-->
<script src="js/lumino.glyphs.js"></script>

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body style="background:white;">
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background: #ececec;">
		<div class="container-fluid">
			<div class="navbar-header" align="center">
				<a class="navbar-brand" href="index.php"><span style="color: #30a5ff;"><b>Image Processing</b></span></a>
			</div>

		</div><!-- /.container-fluid -->
	</nav>

	<div class="col-md-8 col-md-offset-2">



		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">

				</div>
			</div>
		</div><!--/.row-->


		<div class="row" >
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"></use></svg>Upload Your Image:</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="form-group">
                  <form method="post" action="#" enctype="multipart/form-data">
										<div class="form-group">
											<input type="file" name="gambar" class="form-control">
										</div>

										<div class="form-group">
											<p style="color: red;"><?php echo $peringatan; ?></p>
										</div>
										<div class="form-group">
											<button type="submit" name="upload" class="btn btn-primary" style="width: 100%;">Upload</button>
										</div>
                  </form>
								</div>
              </div>
							<div class="col-md-2"></div>
						</div>
					</div>
				</div>

			</div>
			<div class="col-md-12">
				<div class="panel-heading"><svg class="glyph stroked desktop"><use xlink:href="#stroked-desktop"></use></svg>Image Data:</div>
				<table class="table table-striped table-bordered table-hover">
					<tr>
					 <td align="center"><b>ID</b></td>
					 <td align="center"><b>Image</b></td>
					 <td align="center"><b>Title</b></td>
					 <td align="center"><b>Black</b></td>
					 <td align="center"><b>White</b></td>
					 <td align="center"><b>Pixel</b></td>
				 <!--<td align="center"><b>Target</b></td> -->
					 <td align="center"><span class="glyphicon glyphicon-trash"></span></td>
					</tr>
				 <?php
				 $idx=0;
				 $sql = mysql_query("SELECT * FROM img_ext;");
				 while ($isi = mysql_fetch_array($sql)) {
				 	$idx++;
					 ?>
					 <tr align="center">
						 <td align="center"><?php echo $idx; ?></td>
						 <td align="center"><img src="asset/citra/<?php echo $isi['IMAGE']; ?>" height="35px"></td>
						 <td align="center"><?php echo $isi['IMAGE']; ?></td>
						 <td align="center"><?php echo $isi['BLACK']; ?></td>
						 <td align="center"><?php echo $isi['WHITE']; ?></td>
						 <td align="center"><?php echo $isi['SUM_PIXEL']; ?></td>
						 <td align="center"><a href="delete_citra.php?id=<?php echo $isi['ID']; ?>" class="btn btn-danger" style="width: 100%;"><span class="glyphicon glyphicon-trash"></span></a></td>
					</tr>
					 <?php
				 }

				?>

				</table>
			</div>
		</div>


		</div><!--/.row-->
	</div>	<!--/.main-->

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>

	<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
               "language": {
            "lengthMenu": "Menampilkan _MENU_ baris tiap halaman",
            "zeroRecords": "Maaf, Data tidak ditemukan !",
            "info": "Halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)"
        }

        });
    });
    </script>

	<script>
		$('#calendar').datepicker({
		});

		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){
		        $(this).find('em:first').toggleClass("glyphicon-minus");
		    });
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>

</body>

</html>