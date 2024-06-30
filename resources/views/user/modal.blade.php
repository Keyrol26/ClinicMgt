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

                    <form method="post" name="submit"
                        action="{{ route('userappointment.update', $appointment->id) }}">
                        @csrf
                        @method('put')
                        <tr class="fw-bolder ">
                            <th>Appointment Date:</th>
                            <td>
                                <input type="date" name="AppointmentDate" id="AppointmentDate"
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Appointment Date" value="{{ $appointment->AppointmentDate }}" />
                                @error('AppointmentDate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr class="fw-bolder ">
                            <th>Appointment Time :</th>
                            <td>
                                <select name="AppointmentTime" id="AppointmentTime" aria-label="Select Appointment Time"
                                    data-control="select2" data-placeholder="Select an appointment time..."
                                    class="form-select form-select-solid form-select-lg fw-bold">
                                    <option value="{{ $appointment->bookingTime->AppointmentTime }}" selected>
                                        {{ $appointment->bookingTime->AppointmentTime }}
                                    </option>
                                    @foreach ($bookingtime as $booking)
                                        @if ($booking->AppointmentTime != $appointment->bookingTime->AppointmentTime)
                                            <option value="{{ $booking->id }}">
                                                {{ $booking->AppointmentTime }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('AppointmentTime')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
