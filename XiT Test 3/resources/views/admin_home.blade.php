@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-8">
         <div class="card">
            <div class="card-header">{{ __('Dashboard') }}</div>

            <div class="card-body">
               @if (session('status'))
                     <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                     </div>
               @endif

               <div class="table-responsive">
                  <table class="table ">
                     <thead>
                        <tr>
                              <th scope="col">#</th>
                              <th scope="col">Name</th>
                              <th scope="col">Email</th>
                              <th scope="col">Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($users as $user)
                              <tr>
                                 <th scope="row">{{ $loop->iteration }}</th>
                                 <td>{{ $user->name }}</td>
                                 <td>{{ $user->email }}</td>
                                 <td>
                                    @if($user->approved_by_admin)
                                       <button type="button" class="btn btn-warning toggle-verification" data-user-id="{{ $user->id }}">
                                          Revoke Approval
                                       </button>
                                    @else
                                       <button type="button" class="btn btn-success toggle-verification" data-user-id="{{ $user->id }}">
                                          Approve User
                                       </button>
                                    @endif
                                 </td>
                              </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('scripts')
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

   {{-- Sweetalert JS --}}
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>
      $(document).on('click', '.toggle-verification', function(){
         var this_element = $(this)
         var user_id = $(this).data("user-id");

         console.log(this_element, this_element.attr('class'), this_element.attr('class').includes('icon-success'));
         
         $.ajax({
            url: '{{ route("users.toggle_verification_ajax") }}',
            type: 'POST',
            data: {
               "_token": "{{ csrf_token() }}",
               "user_id" : user_id 
            },
            dataType: 'JSON',
            success: function(response)
            {
               if(response.status === 'success'){
                  if(response.verification_status){
                        Swal.fire({
                           icon: 'success',
                           title: 'User verified.',
                           timer: 1500,
                           showConfirmButton: false,
                        })
                  }
                  else{
                        Swal.fire({
                           icon: 'warning',
                           title: 'User verification revoked.',
                           timer: 1500,
                           showConfirmButton: false,
                        })
                  }

                  if(response.verification_status){
                        this_element.removeClass('btn-success').addClass('btn-warning').html('Revoke Approval')
                  }
                  else{
                     this_element.removeClass('btn-warning').addClass('btn-success').html('Approve user')
                  }
               }
            },
            error: function(error){
               if(error.status === 401 && error.responseJSON.error_message){
                  Swal.fire({
                     icon: 'error',
                     title: 'Access Denied!',
                     text: error.responseJSON.error_message,
                     showConfirmButton: false,
                     timer: 1500,
                  })
               }
               else if(error.status === 401){
                  Swal.fire({
                     icon: 'error',
                     title: 'Access Denied!',
                     text: "You don't have the necessary permissions. Please contact system admin for more information.",
                     showConfirmButton: false,
                     timer: 1500,
                  })
               }
               else{
                  Swal.fire({
                     icon: 'error',
                     title: 'Something went wrong!',
                     text: "Please try again later.",
                     timer: 1500,
                     showConfirmButton: false,
                  })
               }
            }
         });
      });
   </script>
@endsection
