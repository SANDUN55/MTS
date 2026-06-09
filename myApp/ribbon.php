					<div class="app-main__inner" >
                        <div class="app-page-title" style="padding: 20px;background-color: #d4cec3"><!--#dcc4aa-->
                            <div class="page-title-wrapper">
                                <div class="page-title-heading" >
                                    <div>  <?php
                                        $sql = "SELECT CONCAT (t_nm, '. ', firstname, ' ', surname ) AS stnm, img, url FROM staff WHERE st_id = $userID";
                                        $result = mysqli_query($conn, $sql) or die(mysqli_error());
                                        if($row = mysqli_fetch_array($result)) {
                                            $img = $row['img'];
                                            echo "<img src=\"https://medicine.kln.ac.lk/images/People/Academic-Staff/$img\" width='60px' align='left'>  ";
                                            echo '&nbsp;' . $row['stnm'].'&nbsp;';
                                        }
                                        ?>
                                        <br>
                                        &nbsp;<?php echo date('d/m/Y') ?>
                                    </div>
                                </div>
                              <!--  <div class="page-title-actions">
                                    <?php
/*                                    $sql = "SELECT CONCAT (t_nm, '. ', firstname, ' ', surname ) AS stnm, img, url FROM staff WHERE st_id = $userID";
                                    $result = mysqli_query($conn, $sql) or die(mysqli_error());
                                    if($row = mysqli_fetch_array($result)) {
                                       echo $row['stnm'].'&nbsp;';
                                       $img = $row['img'];
                                       echo "<img src=\"https://medicine.kln.ac.lk/images/People/Academic-Staff/$img\" width='40px'>";
                                    }
                                    */?>
                                </div>-->
                            </div>
                        </div> 
                    </div>