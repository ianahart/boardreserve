<div>
  <nav id="navbar">
    <div class="header">
      <a href="/">
        <h2>Board<span></span>Reserve</h2>
      </a>
      @if(session('userID'))
      <div class="second-menu">
        <h3>Snowboards <i class="fas fa-sort-up carrot-icon"></i></h3>
        <div class="second-menu-list">
          <ul>
            <li><a href="/snowboards"><i class="fas fa-store"></i> Shop Inventory</a></li>
            <li><a href="/snowboards/create"><i class="fas fa-tags"></i> Sell</a></li>
            <li><a href="/users/snowboards">My Snowboards</a></li>
            <li><a href="/users/cart"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
          </ul>
        </div>
      </div>
      <div class="feed-menu">
        <h3 class="feed-trigger">Feed <i class="fas fa-sort-up feed-icon"></i></h3>
        <div class="feed-menu-list">
          <ul>
            <li><a href="/users?page=1"><i class="fas fa-search"></i> Find Users</a></li>
            <li><a href="/users/feed"><i class="far fa-newspaper"></i> Your Feed</a></li>
            <li><a href="/users/following"><i class="fas fa-glasses"></i> Following</a></li>
          </ul>
        </div>
      </div>
      @endif
    </div>
    <div class="main-nav-wrapper">
      <div class="hamburger">
        <div class="line-container">
          <div class="line-one"></div>
          <div class="line-two"></div>
        </div>
      </div>
      <ul class="main-menu-list">
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        @if(session('userID'))
        <li><a href="/logout">Logout</a></li>
        <a href="/users/profile/me">
          <li class="profile-list-item">
            <div class="profile-tooltip hidden">
              <span>View Profile</span>
            </div>
            <div class="profile-icon-container">
              <i class="fas fa-user-circle profile-icon"></i>
              <div class="profile-user-initials">
                {{ session('userInitials') }}
              </div>
            </div>
          </li>
        </a>
        @else
        <li><a href="/login">Login</a></li>
        <li><a href="/register/create">Register</a></li>
        @endif
      </ul>
    </div>
  </nav>
</div>