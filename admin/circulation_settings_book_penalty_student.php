<div class="col-12 col-md-4 mt-5">
     <div class="card">
          <div class="card-header text-dark  fw-semibold">
               Book Penalty <span class="text-muted small">(per day)</span>
          </div>
          <div class="card-body">
               <table class="table table-striped mt-3">
                    <tbody>
                         <?php
                         $penalty_query= mysqli_query($con,"SELECT * FROM penalty WHERE penalty_id= 1 ");
                         while ($row33= mysqli_fetch_array ($penalty_query) ){
                         $penalty_id=$row33['penalty_id'];
					?>
                         <tr>
                              <td><?php echo '₱&nbsp;'.' '.$row33['penalty_amount']."".'.00'; ?></td>
                              <td style="width: 10px;">
                                   <!-- Modal Button Trigger -->
                                   <span data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Penalty">
                                        <a href="#penalty_edit<?php echo $penalty_id;?>" type="button"
                                             class="btn btn-info text-white" data-bs-toggle="modal"
                                             data-bs-target="#penalty_edit<?php echo $penalty_id;?>">
                                             <i class="bi bi-pencil-square "></i>
                                        </a>
                                   </span>

                              </td>
                              <!-- Modal -->
                              <div class="modal fade" id="penalty_edit<?php echo $penalty_id;?>" tabindex="-1"
                                   aria-labelledby="exampleModalLabel" aria-hidden="true">
                                   <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                             <div class="modal-header">
                                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Book Penalty
                                                  </h1>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                       aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body ">
                                                  <?php
												$query2=mysqli_query($con,"select * from penalty where penalty_id='$penalty_id'")or die(mysqli_error());
												$row44=mysqli_fetch_array($query2);
												?>
                                                  <form method="post" enctype="multipart/form-data"
                                                       class="form-horizontal">
                                                       <div
                                                            class="form-group d-flex align-items-center justify-content-center ">
                                                            <span>₱&nbsp;</span>
                                                            <div class="col-md-3">

                                                                 <input type="number" min="0" max="100" step="1"
                                                                      name="penalty_amount"
                                                                      value="<?php echo $row44['penalty_amount']; ?>"
                                                                      id="first-name2" class="form-control">
                                                            </div>
                                                            <label class="control-label col-md-4 px-2"
                                                                 for="first-name">.00 Amount
                                                            </label>
                                                       </div>

                                             </div>
                                             <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary"
                                                       data-bs-dismiss="modal">Cancel</button>
                                                  <button type="submit" name="student_update_penalty"
                                                       class="btn btn-primary">Update
                                                       Penalty</button>
                                             </div>
                                             </form>
                                        </div>
                                   </div>
                              </div>


                              <?php
                                   if (isset($_POST['student_update_penalty'])) {
                                   
                                   $penalty_amount1 = $_POST['penalty_amount'];
                                   
                                   
                                        mysqli_query($con," UPDATE penalty SET penalty_amount='$penalty_amount1' WHERE penalty_id = '$penalty_id'");
                                   
                                   
                                        echo "<script>alert('Book Penalty Updated Successfully');window.location='circulation_settings'</script>";
                                   
                                        
                                   }
                              ?>

          </div>
     </div>
</div>
</div>

</tr>
<?php } ?>
</tbody>
</table>
</div>
<div class="card-footer">

</div>
</div>
</div>