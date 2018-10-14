<header class="banner">
  <nav class="nav-primary">
    @if (has_nav_menu('nav-primary'))
      {!! wp_nav_menu(['theme_location' => 'nav-primary', 'menu_class' => 'nav']) !!}
    @endif
  </nav>
</header>
