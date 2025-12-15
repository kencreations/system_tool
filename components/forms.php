<form class="bg-white p-4 rounded shadow-sm border border-light">
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" class="form-control" placeholder="name@example.com">
    </div>

    <div class="form-group">
        <label>User Role</label>
        <select class="form-control">
            <option>Administrator</option>
            <option>Editor</option>
            <option>Subscriber</option>
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" class="form-control" placeholder="John">
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" class="form-control" placeholder="Doe">
        </div>
    </div>

    <div class="form-check">
        <input type="checkbox" id="check1" checked>
        <label for="check1">Keep me logged in</label>
    </div>

    <button type="button" class="btn bg-primary text-white w-full mt-2 hover:opacity-90">
        Sign In
    </button>
</form>