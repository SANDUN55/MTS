<?php
session_start();

if(!isset($_SESSION["userLoggedIn"]) || $_SESSION["userLoggedIn"] !== true)
    {
        header('Location:../login.php');
        die();
    }else {
?>
    <?php include 'headtag.php'; ?>
   <script src="assets/scripts/add_class.js"></script>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
 <?php include 'header-top.php'; ?>
<div class="app-main">
<?php include 'navbar-left.php'; ?>
      <div class="app-main__outer">
            <div class="app-main__inner">
         	<div class="main-card mb-3 card">
                <div class="card-body">
<div class="container">
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="registry_home.php">Registries</a></li>
            <li class="active breadcrumb-item" aria-current="page">Class Change Requests</li>
        </ol>
    </nav>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                        <h2><i class="pe-7s-study"></i> <b>Class Change Requests</b></h2>
					</div>
					<div class="col-sm-4" align="right" style="padding-bottom:10px">

					</div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>#</th>
                        <th  align="center">STAFF</th>
						<th  align="center">BATCH - MODULE</th>
                        <th  align="center">ACTIVITY</th>
                        <th  align="center">SCHEDULE</th>
                        <th  align="center">REQUEST</th>
						<th>ACTION</th>
                    </tr>
                </thead>
				<tbody>
				
				<?php
               database_conectivity();
				$result = mysqli_query($conn,"SELECT r.req_id, r.`class_id`, r.`class_topic_id`, t.b_no, t.m_code, t.activity, a.a_name , t.class_topic, t.class_group , r.`class_start` AS rclass_start, r.`class_end`AS rclass_end, 
s.class_start AS oclass_st, s.class_end AS oclass_en, CONCAT (st.t_nm, ' ', st.firstname , ' ', st.surname) as lec 
FROM classsreq r
JOIN classtopics t ON r.class_topic_id = t.topic_id 
JOIN classschedules s ON r.class_id= s.class_id 
JOIN activity a ON t.activity = a.a_id
JOIN staff st ON st.st_id=t.staff
WHERE req_status = 0 ORDER BY r.add_dt DESC;");
					$i=1;
					while($row = mysqli_fetch_array($result)) {
                        $phpdate = strtotime($row["oclass_st"] );
                        $dt = date( 'Y-m-d H:i', $phpdate );
                        $phpdate = strtotime( $row["oclass_en"] );
                        $enTm = date( 'H:i', $phpdate );
                        $des = $dt . ' - ' . $enTm ;
                        $phpdate = strtotime($row["rclass_start"] );
                        $dtr = date( 'Y-m-d H:i', $phpdate );
                        $phpdate = strtotime( $row["rclass_end"] );
                        $enTmr = date( 'H:i', $phpdate );
                        $desr = $dtr . ' - ' . $enTmr ;
				?>
				<tr id="<?php echo $row["req_id"]; ?>">
					<td><?php echo $i; ?></td>
                    <td align="center"><?php echo $row["lec"] ; ?></td>
					<td align="center"><?php echo $row["b_no"]. ' - ' .$row["m_code"] ; ?></td>
					<td align="left"><?php echo $row["a_name"]. ' : ' .$row["class_topic"] ; ?></td>
                    <td><?php echo $des; ?></td>
                    <td align="left"><?php echo $desr; ?></td>
                    <td align="center">
                        <a href="#approveReq" class="edit" data-toggle="modal">
                            <i class="pe-7s-pen icon-gradient bg-sunny-morning aprRq" data-toggle="tooltip"
                               data-idc="<?php echo $row["class_id"]; ?>"
                               data-idt="<?php echo $row["class_topic_id"]; ?>"
                               data-cst="<?php echo $row["rclass_start"]; ?>"
                               data-cen="<?php echo $row["rclass_end"]; ?>"
                               data-rid="<?php echo $row["req_id"]; ?>"
                               title="Edit"></i>
                        </a>
						 <a href="#rejectReq" class="reject" data-rd="<?php echo $row["req_id"]; ?>" data-toggle="modal"><i class="pe-7s-close-circle  icon-gradient bg-happy-itmeo" data-toggle="tooltip" title="Reject"></i></a>
                        </td>
				</tr>
				<?php
				$i++;
				}
				?>
				</tbody>
			</table>
        </div>
	</div>
			</div>		
                    </div>
                <?php include 'footer.php'; ?>

            </div>
        </div>
    </div>
<script type="text/javascript" src="assets/scripts/main.js"></script></body>
    <div id="approveReq" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="frmReqData">
                    <div class="modal-header">
                        <h4 class="modal-title">Approve Class Change Request</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="cid_u" name="classId">
                        <input type="hidden" id="tid_u" name="topicId">
                        <input type="hidden" id="cst_u" name="classSt">
                        <input type="hidden" id="cen_u" name="classEn">
                        <input type="hidden" id="rid_u" name="reqId">
                        <input type="hidden" id="uid_u" name="userId" value="<?php echo $_SESSION['userMtsFom']; ?>">
                        <input type="hidden" id="type" name="type" value="9">
                        <p>Are you sure you want to <span class="text-danger">Approve</span> this Request?</p>
                        <p class="text-primary"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <button type="button" class="btn btn-primary" id="aprClsReq">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Disable Modal HTML -->
<div id="rejectReq" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frmRejData">

                <div class="modal-header">
                    <h4 class="modal-title">Reject Class Change Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="rid1" name="reqId">
                    <input type="hidden" id="uid" name="userId" value="<?php echo $_SESSION['userMtsFom']; ?>">
                    <input type="hidden" id="type" name="type" value="10">
                    <p>Are you sure you want to <span class="text-danger">REJECT</span> this Request?</p>
                    <p class="text-primary"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <button type="button" class="btn btn-danger" id="rejClsReq">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
</html>
<?php } ?>