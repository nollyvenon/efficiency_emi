<form method="POST" action="{{ route('applicant.register') }}">
    @csrf
    <input type="text" name="name" required placeholder="Full Name">
    <input type="email" name="email" required placeholder="Email">
    <input type="tel" name="phone" required placeholder="Phone">
    <textarea name="details" placeholder="Additional Details"></textarea>
    <button type="submit">Register</button>
</form>
