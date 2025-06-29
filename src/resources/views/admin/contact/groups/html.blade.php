@push("style-include")
  <link rel="stylesheet" href="{{ asset('assets/theme/global/css/select2.min.css')}}">
@endpush

@extends('admin.layouts.app')
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
                  <a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> {{ $title }} -  {{ Str::upper($group->name) }}</li>
              </ol>
            </nav>
          </div>
          <div class="">
                <div class="pt-5 position-relative">
                   <button id="copyButton" style="position: absolute; top: 54px; right: 27px; padding: 5px 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        <i class="fas fa-copy" style="margin-right: 5px; cursor: pointer;" id="copyButton"></i> Copy Code
                         <!--<i data-feather="code" class="w-4 h-4">Copy Code</i>-->
                    </button>
                    <textarea id="htmlContent" style="width: 100%; height: 400px; border: 1px solid #ccc; padding: 10px;" readonly></textarea>
                </div>
            </div>
        </div>
      </div>
      <div class="table-filter mb-4">
       
    </div>
      
    </div>
</main>

@endsection
@section("modal")

@endsection
@push("script-include")
  <script src="{{asset('assets/theme/global/js/select2.min.js')}}"></script>
@endpush

@push('script-push')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const url = '{{ route("admin.contact.group.download", $group->id) }}'; // Set the URL to fetch the HTML file

        try {
            const response = await fetch(url);
            
            if (!response.ok) throw new Error('Network response was not ok');

            const htmlContent = await response.text();
            console.log("HTML Content:", htmlContent);
            
            // Set the content of the textarea
            document.getElementById('htmlContent').value = htmlContent;
        } catch (error) {
            console.error('Error fetching HTML file content:', error);
            alert('Failed to load HTML file content.');
        }

        // Add event listener to the copy button
        document.getElementById('copyButton').addEventListener('click', function() {
            copyToClipboard(document.getElementById('htmlContent').value);
        });
    });

    function copyToClipboard(htmlContent) {
        const tempTextarea = document.createElement('textarea');
        tempTextarea.value = htmlContent;
        document.body.appendChild(tempTextarea);
        tempTextarea.select();
        document.execCommand('copy');
        document.body.removeChild(tempTextarea);
        alert('HTML code copied to clipboard!');
    }
</script>
@endpush

