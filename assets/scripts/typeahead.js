var typeahead_videos = ['Test', 'Video', 'Titles'];
$(document).ready(function(){
    $('.typeahead').typeahead({
      minLength: 1,
      highlight: true,
    },
    {
      name: 'my-dataset',
      source: typeahead_videos
    });
});