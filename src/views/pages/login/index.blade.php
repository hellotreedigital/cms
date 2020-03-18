@extends('cms::layouts/main')

@section('main-content')

	<div id="login" class="d-table w-100 h-100 py-5">
		<div class="d-table-cell align-middle py-5 px-3">
			<div class="card m-auto px-0 px-md-5 py-3 py-md-5">
				<div class="row py-3 px-3">
					<div class="col-md-6 pr-md-5 text-center">
						<div class="d-table w-100 h-100">
							<div class="d-table-cell align-middle">
								<img class="w-100 mb-4 mb-md-3" src="{{ url('asset?path=images/logo.png') }}">
								<p class="font-weight-bold mb-4 mb-md-5 mb-md-0 d-none d-md-block">Thank You for Choosing Hellotree <3</p>
							</div>
						</div>
					</div>
					<div class="col-md-6 pl-md-5 text-center">
						<h4 class="font-weight-bold mb-4">Login</h4>
						@if ($errors->any())
							<div class="alert alert-danger">
								@foreach ($errors->all() as $error)
									<p class="m-0">{{ $error }}</p>
								@endforeach
							</div>
						@endif
						@if (Session::has('error'))
							<div class="alert alert-danger">
								<p class="m-0">{{ Session::get('error') }}</p>
							</div>
						@endif
						<form method="post">
							@csrf
							<div class="form-group text-left">
								<label>Email</label>
								<input class="form-control" name="email" value="{{ old('email') }}">
							</div>
							<div class="form-group text-left">
								<label>Password</label>
								<input class="form-control" type="password" name="password">
							</div>
							<div class="text-right mt-4">
								<button class="btn btn-sm btn-primary px-4">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="login-footer-wrapper">
		@include('cms::components/footer')
	</div>

@endsection