<?php

/*
* Register spirit event custom post type
*/
function tssev_post_type() {

  register_post_type( 'spirit-events',
    array(
      'labels' => array(
        'name' => __( 'Events','spirit-events' ),
        'singular_name' => __( 'Event','spirit-events' ),
        'edit_item' => __( 'Update event','spirit-events' )     
      ),
	  'public' => true,
      'supports' => array( 'title','editor','thumbnail','revisions','page-attributes','author'),	  
      'has_archive' => true,
      'menu_icon' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDQyNi42NjcgNDI2LjY2NyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDI2LjY2NyA0MjYuNjY3OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4Ij4KPGc+Cgk8Zz4KCQk8Zz4KCQkJPHBhdGggZD0iTTM2Mi42NjcsNDIuNjY3aC0yMS4zMzNWMGgtNDIuNjY3djQyLjY2N0gxMjhWMEg4NS4zMzN2NDIuNjY3SDY0Yy0yMy41NzMsMC00Mi40NTMsMTkuMDkzLTQyLjQ1Myw0Mi42NjdMMjEuMzMzLDM4NCAgICAgYzAsMjMuNTczLDE5LjA5Myw0Mi42NjcsNDIuNjY3LDQyLjY2N2gyOTguNjY3YzIzLjU3MywwLDQyLjY2Ny0xOS4wOTMsNDIuNjY3LTQyLjY2N1Y4NS4zMzMgICAgIEM0MDUuMzMzLDYxLjc2LDM4Ni4yNCw0Mi42NjcsMzYyLjY2Nyw0Mi42Njd6IE0zNjIuNjY3LDM4NEg2NFYxNDkuMzMzaDI5OC42NjdWMzg0eiIgZmlsbD0iIzAwMDAwMCIvPgoJCQk8cG9seWdvbiBwb2ludHM9IjMwOS45NzMsMjE0LjYxMyAyODcuMzYsMTkyIDE4My4yNTMsMjk2LjEwNyAxMzguMDI3LDI1MC44OCAxMTUuNDEzLDI3My40OTMgMTgzLjI1MywzNDEuMzMzICAgICIgZmlsbD0iIzAwMDAwMCIvPgoJCTwvZz4KCTwvZz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K',
    'rewrite' => array('slug' => __( 'events','spirit-events' )),
    'show_in_rest' => true
    )
  );
}
add_action( 'init', 'tssev_post_type' );