<div class="container">
    <h1>Add New Class</h1>

    <form id="addClassForm" class="form-box">
        <div class="form-group">
            <label for="class_name">Class Name:</label>
            <input type="text" id="class_name" name="class_name" placeholder="Enter Class Name" required>
        </div>

        <div class="form-group">
            <label for="trainer">Trainer ID:</label>
            <input type="text" id="trainer" name="trainer" placeholder="Enter Trainer ID" required>
        </div>

        <div class="form-group">
            <label for="date">Class Date:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="form-group">
            <label for="time">Class Time:</label>
            <input type="time" id="time" name="time" required>
        </div>

        <button type="submit" class="btn-submit">Add Class</button>
    </form>
</div>

