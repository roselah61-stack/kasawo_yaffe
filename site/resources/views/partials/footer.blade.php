<footer class="site-footer">
    <div class="wrap">
        <div class="foot-grid" data-stagger=".1">
            <div>
                <x-logo light style="margin-bottom:16px" />
                <p>Our home, our story, our people. Kasawo online: its history, places to visit, and the businesses that keep it going.</p>
                <p style="font-size:.85rem">Kasawo, Mukono District · Central Uganda<br>{{ config('kasawo.phone') }} · {{ config('kasawo.email') }}</p>
            </div>
            <div>
                <h4>Discover</h4>
                <ul>
                    <li><a href="{{ route('story') }}">Our Story</a></li>
                    <li><a href="{{ route('explore') }}">Places to visit</a></li>
                    <li><a href="{{ route('explore') }}#events">Events</a></li>
                    <li><a href="{{ route('story') }}#gallery">Photo gallery</a></li>
                </ul>
            </div>
            <div>
                <h4>Get involved</h4>
                <ul>
                    <li><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
                    <li><a href="{{ route('marketplace.create') }}">List your business</a></li>
                    <li><a href="{{ route('donate.create') }}">Donate</a></li>
                    <li><a href="{{ route('donate.create') }}#partners">Partner with us</a></li>
                </ul>
            </div>
            <div>
                <h4>Stay in touch</h4>
                <p style="font-size:.9rem">News, events and new businesses, once a month.</p>
                <form class="newsletter" method="POST" action="{{ route('subscribe') }}">
                    @csrf
                    <input name="contact" placeholder="Your email or phone" required aria-label="Email or phone">
                    <button class="btn btn-primary btn-sm">Join</button>
                </form>
                @error('contact')<small style="color:#f3b0a8">{{ $message }}</small>@enderror
            </div>
        </div>
        <div class="foot-bottom">
            <span>© {{ date('Y') }} Kasawo Yaffe · Webale kujja (thank you for visiting)</span>
            <span><a href="{{ asset('images/CREDITS.md') }}">Photo credits</a></span>
        </div>
    </div>
</footer>
