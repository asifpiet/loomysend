@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush

@extends('user.layouts.app')
@section('panel')

<main class="main-body">
    <div class="container-fluid px-0 main-content">
      <div class="page-header">
        <div class="page-header-left">
          <h2>{{ $title }}</h2>
          <div class="breadcrumb-wrapper">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="{{ route("user.dashboard") }}">{{ translate("Dashboard") }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> {{ $title }} </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <div class="table-filter mb-4">
        <form action="{{route(Route::currentRouteName())}}" class="filter-form">
            
            <div class="row g-3">
                <div class="col-lg-3">
                    <div class="filter-search">
                        <input type="search" value="{{request()->search}}" name="search" class="form-control" id="filter-search" placeholder="{{ translate("Search by name") }}" />
                        <span><i class="ri-search-line"></i></span>
                    </div>
                </div>

                <div class="col-xxl-8 col-lg-9 offset-xxl-1">
                    <div class="filter-action">
                        <select data-placeholder="{{translate('Select A Status')}}" class="form-select select2-search" name="status" aria-label="Default select example">
                            <option value=""></option>
                            <option {{ request()->status == \App\Enums\StatusEnum::TRUE->status() ? 'selected' : ''  }} value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Active") }}</option>
                            <option {{ request()->status == \App\Enums\StatusEnum::FALSE->status() ? 'selected' : ''  }} value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Inactive") }}</option>
                        </select>
                        <div class="input-group">
                            <input type="text" class="form-control" id="datePicker" name="date" value="{{request()->input('date')}}"  placeholder="{{translate('Filter by date')}}"  aria-describedby="filterByDate">
                            <span class="input-group-text" id="filterByDate">
                                <i class="ri-calendar-2-line"></i>
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="filter-action-btn ">
                                <i class="ri-menu-search-line"></i> {{ translate("Filter") }}
                            </button>
                            <a class="filter-action-btn bg-danger text-white" href="{{route(Route::currentRouteName())}}">
                                <i class="ri-refresh-line"></i> {{ translate("Reset") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
      <div class="card">
        <div class="card-header">
            <div class="card-header-left">
                <h4 class="card-title">{{ translate("Contacts") }}</h4>
            </div>
            <div class="card-header-right">
                <div class="d-flex gap-3 align-item-center">
                    <button class="bulk-action i-btn btn--danger btn--sm bulk-delete-btn d-none">
                        <i class="ri-delete-bin-6-line"></i>
                    </button>

                    <div class="bulk-action form-inner d-none">
                        <select class="form-select" data-show="5" id="bulk_status" name="status">
                            <option disabled selected>{{ translate("Select a status") }}</option>
                            <option value="{{ \App\Enums\StatusEnum::TRUE->status() }}">{{ translate("Enabled") }}</option>
                            <option value="{{ \App\Enums\StatusEnum::FALSE->status() }}">{{ translate("Disabled") }}</option>
                        </select>
                    </div>

                    <div class="card-header-right">
                        <button class="i-btn btn--primary btn--sm add-contact-group" type="button" data-bs-toggle="modal" data-bs-target="#addContactGroup">
                            <i class="ri-add-fill fs-16"></i> {{ translate("Add Group") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body px-0 pt-0">
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th scope="col">
                    <div class="form-check">
                      <input class="check-all form-check-input" type="checkbox" value="" id="checkAll" />
                      <label class="form-check-label" for="checkedAll"> {{ translate("SL No.") }} </label>
                    </div>
                  </th>
                  <th scope="col">{{ translate("Group Name") }}</th>
                  <th scope="col">{{ translate("Contacts") }}</th>
                  <th scope="col">{{ translate("Status") }}</th>
                  <th scope="col">{{ translate("Option") }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($contact_groups as $contact_group)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" value="{{$contact_group->id}}" name="ids[]" class="data-checkbox form-check-input" id="{{$contact_group->id}}" />
                                <label class="form-check-label fw-semibold text-dark" for="bulk-{{$loop->iteration}}">{{$loop->iteration}}</label>
                            </div>
                        </td>
                        <td> {{ $contact_group->name }} </td>
                        <td data-label="{{ translate('Group')}}">
                            <a href="{{route('user.contact.index', $contact_group->id)}}" class="badge badge--primary p-2">
                                <span class="i-badge info-solid pill">
                                    {{translate("View All: ").count($contact_group->contacts).translate(" contacts")}} <i class="ri-eye-line ms-1"></i>
                                </span>
                            </a>
                        </td>
                        <td data-label="{{ translate('Status')}}">
                            <div class="switch-wrapper checkbox-data">
                                <input {{ $contact_group->status == \App\Enums\StatusEnum::TRUE->status() ? 'checked' : '' }}
                                        type="checkbox"
                                        class="switch-input statusUpdate"
                                        data-id="{{ $contact_group->id }}"
                                        data-column="status"
                                        data-value="{{ $contact_group->status }}"
                                        data-route="{{route('user.contact.group.status.update')}}"
                                        id="{{ 'status_'.$contact_group->id }}"
                                        name="status"/>
                                <label for="{{ 'status_'.$contact_group->id }}" class="toggle">
                                    <span></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <button class="icon-btn btn-ghost btn-sm success-soft circle edit-contact-group"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editContactGroup"
                                        href="javascript:void(0)"
                                        data-uid    = "{{$contact_group->uid}}"
                                        data-name   = "{{$contact_group->name}}">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button class="icon-btn btn-ghost btn-sm danger-soft circle text-danger delete-contact-group"
                                        type="button"
                                        data-delete_uid="{{$contact_group->uid}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteContactGroup">
                                    <i class="ri-delete-bin-line"></i>
                                    <span class="tooltiptext"> {{ translate("Delete Single Contact") }} </span>
                                </button>
                                
                                
                            </div>
                        </td>
                    </tr>
                @empty

                @endforelse
              </tbody>
            </table>
          </div>
          @include('user.partials.pagination', ['paginator' => $contact_groups])
        </div>
      </div>
    </div>
</main>

@endsection
@section("modal")
<div class="modal fade" id="addContactGroup" tabindex="-1" aria-labelledby="addContactGroup" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.contact.group.save')}}" method="POST" enctype="multipart/form-data">
				@csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Add Contact Group") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="add_name" class="form-label"> {{ translate('Contact Group Name')}} </label>
                                <input type="text" id="add_name" name="name" placeholder="{{ translate('Enter contact group name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editContactGroup" tabindex="-1" aria-labelledby="editContactGroup" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered ">
        <div class="modal-content">
            <form action="{{route('user.contact.group.save')}}" method="POST" enctype="multipart/form-data">
				@csrf
                <input type="hidden" name="uid">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ translate("Update Contact Group") }} </h5>
                    <button type="button" class="icon-btn btn-ghost btn-sm danger-soft circle modal-closer" data-bs-dismiss="modal">
                        <i class="ri-close-large-line"></i>
                    </button>
                </div>
                <div class="modal-body modal-md-custom-height">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="form-inner">
                                <label for="add_name" class="form-label"> {{ translate('Contact Group Name')}} </label>
                                <input type="text" id="add_name" name="name" placeholder="{{ translate('Enter contact group name')}}" class="form-control" aria-label="name"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="i-btn btn--danger outline btn--md" data-bs-dismiss="modal"> {{ translate("Close") }} </button>
                    <button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Save") }} </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade actionModal" id="deleteContactGroup" tabindex="-1" aria-labelledby="deleteContactGroup" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.contact.group.delete')}}" method="POST">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="uid" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to delete this contact group?") }}</h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Delete") }} </button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade actionModal" id="bulkAction" tabindex="-1" aria-labelledby="bulkAction" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
        <div class="modal-header text-start">
            <span class="action-icon danger">
            <i class="bi bi-exclamation-circle"></i>
            </span>
        </div>
        <form action="{{route('user.contact.group.bulk')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" name="id" value="">
                <div class="action-message">
                    <h5>{{ translate("Are you sure to change the status for the selected data?") }}</h5>
                    <p>{{ translate("This action is irreversable") }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="i-btn btn--dark outline btn--lg" data-bs-dismiss="modal"> {{ translate("Cancel") }} </button>
                <button type="submit" class="i-btn btn--danger btn--lg" data-bs-dismiss="modal"> {{ translate("Proceed") }} </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush

@push('script-push')
<script>
    (function($){
        "use strict";

        select2_search($('.select2-search').data('placeholder'));
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            mode: "range",
        });

        $(document).ready(function() {

            $('.add-contact-group').on('click', function() {

                var modal = $('#addContactGroup');
                modal.modal('show');
            });
            $('.edit-contact-group').on('click', function() {

                var modal = $('#editContactGroup');
                modal.find('input[name=uid]').val($(this).data('uid'));
			    modal.find('input[name=name]').val($(this).data('name'));
                modal.modal('show');
            });
            $('.delete-contact-group').on('click', function(){
                var modal = $('#deleteContactGroup');
                modal.find('input[name=uid]').val($(this).data('delete_uid'));
            });
            $('.checkAll').click(function() {

                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        });

    })(jQuery);


</script>
@endpush

