@include('front.IncludesFiles.head')
@include('front.IncludesFiles.menu')

@include('front.IncludesFiles.page-title', ['title' => 'Job Opening'])

<div role="main" class="main">
    <section>
        <div class="container my-5" id="jobs-container">
            <!-- Jobs will be dynamically appended here -->
        </div>
        <div class="text-center" id="loading-spinner" style="display: none;">
            <p>Loading...</p>
        </div>
    </section>
</div>

@include('front.IncludesFiles.footer')