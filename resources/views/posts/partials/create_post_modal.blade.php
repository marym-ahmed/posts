<!-- HTML Structure for the Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="create-post-form" action="{{ route('posts.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Translations for Title and Content -->
                    @foreach (config('translatable.locales') as $locale)
                        <div class="form-group">
                            <label for="title_{{ $locale }}">Title ({{ $locale }})</label>
                            <input type="text" name="title[{{ $locale }}]" id="title_{{ $locale }}"
                                class="form-control" value="{{ old('title.' . $locale) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="content_{{ $locale }}">Content ({{ $locale }})</label>
                            <textarea name="content[{{ $locale }}]" id="content_{{ $locale }}" class="form-control" required>{{ old('content.' . $locale) }}</textarea>
                        </div>
                    @endforeach

                    <!-- Thumbnail -->
                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                    </div>

                    <!-- Existing Tags -->
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <select name="tags[]" id="tags" class="form-control" multiple>
                            @foreach ($tags as $tag)
                                <option
                                    value="{{ $tag->id }}"{{ in_array($tag->id, old('tags', [])) ? ' selected' : '' }}>
                                    {{ $tag->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Existing Tags -->


                    <!-- New Tags -->
                    <div class="form-group">
                        <label>New Tags</label>
                        <div id="new-tags-wrapper">
                            @foreach (config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="new_tags_{{ $locale }}">New Tags ({{ $locale }})</label>
                                    <input type="text" name="new_tags[{{ $locale }}]"
                                        id="new_tags_{{ $locale }}" class="form-control"
                                        placeholder="Add new tags separated by commas"
                                        value="{{ old('new_tags.' . $locale) }}">
                                </div>
                            @endforeach
                        </div>


                        <button type="button" class="btn btn-secondary"
                            id="add-new-tag">{{ __('Add New Tag') }}</button>
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>




<script>
    $('#add-new-tag').off('click').on('click', function() {
        var newTagsWrapper = $('#new-tags-wrapper');
        var locales = @json(config('translatable.locales')); // Pass locales to JavaScript

        var localeFields = '';
        locales.forEach(function(locale) {
            localeFields += '<div class="form-group">';
            localeFields += '<label for="new_tags_' + locale + '">New Tags (' + locale + ')</label>';
            localeFields += '<input type="text" name="new_tags[' + locale + ']" id="new_tags_' +
                locale + '" class="form-control" placeholder="Add new tags separated by commas">';
            localeFields += '</div>';
        });

        newTagsWrapper.append(localeFields);
    });
</script>
