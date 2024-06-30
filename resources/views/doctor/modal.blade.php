<div class="modal fade" id="statusmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Take Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover data-tables">

                    <form method="post" name="submit" action="{{ route('appointment.update', $appointment->id) }}">
                        @csrf
                        @method('put')
                        <tr class="fw-bolder ">
                            <th>Remark :</th>
                            <td>
                                <textarea name="Remark" placeholder="Remark" rows="12" cols="14" class="form-control wd-450" required="true">{{ $appointment->Remark }}</textarea>
                            </td>
                        </tr>

                        <tr class="fw-bolder ">
                            <th>Status :</th>
                            <td>
                                <select name="Status" class="form-control wd-450" required="true">
                                    <option value="Approved" {{ $appointment->Status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Cancelled" {{ $appointment->Status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="Rescheduled" {{ $appointment->Status == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                </select>
                            </td>
                        </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary">Update</button>

                </form>


            </div>


        </div>
    </div>

</div>

<div class="modal fade" id="reportmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover data-tables">

                    <form method="post" name="submit" action="{{ route('appointmentreport.update', $appointment->id) }}">
                        @csrf
                        @method('put')
                        <tr class="fw-bolder ">
                            <th>Appointment Report :</th>
                            <td>
                                <textarea type="text" name="docmsg" placeholder="Appointment Report" rows="5" cols="25"
                                    class="form-control wd-700" required="true">{{ $appointment->DocMsg }}</textarea>
                            </td>
                        </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>

</div>
