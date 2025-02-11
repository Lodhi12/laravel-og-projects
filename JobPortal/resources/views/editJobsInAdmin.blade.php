@extends('layout.masterlayout')
@section('jobsInAdmin')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('adminSidebar')
                </div>
                <div class="col-lg-9">
                    @include('message')
                    <form action="" method="POST" id="editJobForm" name="editJobForm">
                        @csrf
                        <div class="card border-0 shadow mb-4 ">
                            <div class="card-body card-form p-4">
                                <h3 class="fs-4 mb-1">Job Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Title<span class="req">*</span></label>
                                        <input type="text" value="{{ $job->title }}" placeholder="Job Title" id="title" name="title" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="" class="mb-2">Category<span class="req">*</span></label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select a Category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option {{ $job->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Job Type<span class="req">*</span></label>
                                        <select class="form-select" name="jobType" id="jobType">
                                            <option value="">Select a Job Type</option>
                                            @if ($jobTypes->isNotEmpty())
                                                @foreach ($jobTypes as $jobType)
                                                    <option {{ $job->job_type_id == $jobType->id ? 'selected' : '' }} value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                @endforeach                                            
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                        <input type="number" value="{{ $job->vacancy }}" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
    
                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Salary</label>
                                        <input type="text" value="{{ $job->salary }}" placeholder="Salary" id="salary" name="salary" class="form-control">
                                    </div>
    
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Location<span class="req">*</span></label>
                                        <input type="text" value="{{ $job->location }}" placeholder="location" id="location" name="location" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <div class="form-check">
                                            <input {{ ($job->isFeatured == 1) ? 'checked' : '' }} type="checkbox" value="1" id="isFeatured" name="isFeatured" class="form-check-input">
                                            <label for="flexCheckDefault" class="form-check-label">Featured</label>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <div class="form-check-inline">
                                            <input {{ ($job->status == 1) ? 'checked' : '' }} type="radio" value="1" id="status-active" name="status" class="form-check-input">
                                            <label for="status" class="form-check-label">Active</label>
                                        </div>
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <div class="form-check-inline">
                                            <input {{ ($job->status == 0) ? 'checked' : '' }} type="radio" value="0" id="status-block" name="status" class="form-check-input">
                                            <label for="status" class="form-check-label">Block</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="" class="mb-2">Description<span class="req">*</span></label>
                                    <textarea class="textarea" name="description" id="description" cols="5" rows="5" placeholder="Description">{{ $job->description }}</textarea>
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Benefits</label>
                                    <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits">{{ $job->benefits }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Responsibility</label>
                                    <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5" placeholder="Responsibility">{{ $job->responsibility }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Qualifications</label>
                                    <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5" placeholder="Qualifications">{{ $job->qualifications }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                    <select name="experience" id="experience" class="form-control">
                                        <option value="1" {{ ($job->experience == 1) ? 'selected': '' }}>1 Year</option>
                                        <option value="2" {{ ($job->experience == 2) ? 'selected': '' }}>2 Years</option>
                                        <option value="3" {{ ($job->experience == 3) ? 'selected': '' }}>3 Years</option>
                                        <option value="4" {{ ($job->experience == 4) ? 'selected': '' }}>4 Years</option>
                                        <option value="5" {{ ($job->experience == 5) ? 'selected': '' }}>5 Years</option>
                                        <option value="6" {{ ($job->experience == 6) ? 'selected': '' }}>6 Years</option>
                                        <option value="7" {{ ($job->experience == 7) ? 'selected': '' }}>7 Years</option>
                                        <option value="8" {{ ($job->experience == 8) ? 'selected': '' }}>8 Years</option>
                                        <option value="9" {{ ($job->experience == 9) ? 'selected': '' }}>9 Years</option>
                                        <option value="10" {{ ($job->experience == 10) ? 'selected': '' }}>10 Years</option>
                                        <option value="10_plus" {{ ($job->experience == '10_plus') ? 'selected': '' }}>10+ Years</option>
                                    </select>
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Keywords</label>
                                    <input type="text" value="{{ $job->keywords }}" placeholder="keywords" id="keywords" name="keywords" class="form-control">
                                </div>
    
                                <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>
    
                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Name<span class="req">*</span></label>
                                        <input type="text" value="{{ $job->company_name }}" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                        <p></p>
                                    </div>
    
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Location</label>
                                        <input type="text" value="{{ $job->company_location }}" placeholder="Location" id="company_location" name="company_location" class="form-control">
                                    </div>
                                </div>
    
                                <div class="mb-4">
                                    <label for="" class="mb-2">Website</label>
                                    <input type="text" value="{{ $job->company_website }}" placeholder="Website" id="company_website" name="company_website" class="form-control">
                                </div>
                            </div> 
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update Job</button>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        function deleteUser(id){
            if((confirm)"Are you sure you want to delete this user?"){
                $.ajax({
                    url: '{{ route("admin.users.destroy")}}',
                    type: 'DELETE',
                    data: {},
                    dataType: {},
                    success: function(response){
                        window.location.href="{{ route('admin.users') }}";
                    }
                });
            }
        }
    </script>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#editJobForm").submit(function (e){
            e.preventDefault();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route("updateJob", $job->id) }}',
                type: 'POST',
                dataType: 'json',
                data: $("#editJobForm").serializeArray(),
                success: function(response){
                    $("button[type='submit']").prop('disabled', false);
                    if(response.status == true){
                        
                        $("#title").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        $("#jobType").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        $("#vacancy").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        $("#location").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        $("#description").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        $("#company_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')

                        window.location.href="{{ route('myJobs') }}"
                    }

                    else{
                        var errors = response.errors;

                        if(errors.title){
                            $("#title").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.title)
                        }

                        else{
                            $("#title").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors.category){
                            $("#category").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.category)
                        }

                        else{
                            $("#category").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors.jobType){
                            $("#jobType").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.jobType)
                        }

                        else{
                            $("#jobType").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors.vacancy){
                            $("#vacancy").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.vacancy)
                        }

                        else{
                            $("#vacancy").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors.location){
                            $("#location").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.location)
                        }

                        else{
                            $("#location").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors.description){
                            $("#description").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.description)
                        }

                        else{
                            $("#description").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }

                        if(errors.company_name){
                            $("#company_name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.company_name)
                        }

                        else{
                            $("#company_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('')
                        }
                    }
                }
            })
        });
    </script>
@endsection
