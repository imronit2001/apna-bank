@extends('customer.homesidenav')
@section('title','Transaction Password')
@section('page-name')
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Launch static backdrop modal
</button>
<div class="container justify-content-center tran-pass ">
        <div class="d-flex justify-content-center align-item-center mt-10 p-5 ">
            <form>
                <h2>Transection Password</h2>
                <div class="form-group mt-4">
                  <label for="formGroupExampleInput">New Password</label>
                  <input type="password" class="form-control" id="" placeholder="Enter Password">
                </div>
                <div class="form-group mt-3">
                  <label for="formGroupExampleInput2">Confirm Password</label>
                  <input type="password" class="form-control" id="" placeholder="Confirm Password">
                </div>
                <div class="form-group text-center mt-5">
                    <button type="button" class="btn btn-block">Set Password</button>
                </div>
              </form>
        </div>
    </div>
    @endsection