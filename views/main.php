
<h1><?php echo esc_html__('Certificate Check', 'certificate-check'); ?></h1>
<p><?php echo esc_html__('Add certificates to be checked on website', 'certificate-check'); ?></p>

<form method="POST" action="<?php echo esc_html(admin_url('admin-post.php')); ?>" class="mb-3">
    <input type="hidden" name="action" value="certificate_check_admin_save">
    <?php wp_nonce_field('certificate_check_admin_save', 'certificate_check_admin'); ?>
    <input type="hidden" name="redirectToUrl" value="<?php echo certificate_check_admin_view_pagename(''); ?>">
    <div class="row g-3">
        <div class="col-md-4 ">
            <label for="certificate_number" class="form-label">
                Certificate Number
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="certificate_number" name="certificate_number" placeholder="ABC12345" autocomplete="certificate_number" required>
        </div>
        <div class="col-md-4 ">
            <label for="first_name" class="form-label">
                First Name
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Jhon" autocomplete="first_name" required>
        </div>
        <div class="col-md-4 ">
            <label for="last_name" class="form-label">
                Last Name
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe" autocomplete="last_name" required>
        </div>
        <div class="col-md-4 ">
            <label for="email" class="form-label">
                Email
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" autocomplete="email" required>
        </div>
        <div class="col-md-4 ">
            <label for="mobile" class="form-label">
                Mobile
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="(912) 345-67-89" autocomplete="mobile" required>
        </div>
        <div class="col-md-4 ">
            <label for="product" class="form-label">
                Product
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="product" name="product" placeholder="MSC in Computer Science" autocomplete="product" required>
        </div>
        <div class="col-md-4 ">
            <label for="issue_date" class="form-label">
                Issue Date
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="date" class="form-control" id="issue_date" name="issue_date" autocomplete="issue_date" required>
        </div>
        <div class="col-md-4 ">
            <label for="exam_date" class="form-label">
                Exam Date
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="date" class="form-control" id="exam_date" name="exam_date" autocomplete="exam_date" required>
        </div>
        <div class="col-md-4 ">
            <label for="result" class="form-label">
                Result
                <sup><small><i class="bi bi-asterisk text-danger"></i></small></sup>
            </label>
            <input type="text" class="form-control" id="result" name="result" placeholder="Pass" autocomplete="result" required>
        </div>
        <div class="col-md-4">
            <button class="btn btn-dark">Add Certificate</button>
        </div>
    </div>
</form>

<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Certificate Number</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Product</th>
            <th>Issue Date</th>
            <th>Exam Date</th>
            <th>Result</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (get_certificates() as $row) {
            echo "<tr>
                <td>".$row->id."</td>
                <td>".$row->certificate_number."</td>
                <td>".$row->first_name."</td>
                <td>".$row->last_name."</td>
                <td>".$row->email."</td>
                <td>".$row->mobile."</td>
                <td>".$row->product."</td>
                <td>".$row->issue_date."</td>
                <td>".$row->exam_date."</td>
                <td>".$row->result."</td>
                <td>
                    <a href='".certificate_check_admin_view_pagename('edit')."&id=".$row->id."' class='bg-primary text-white px-2 py-1 rounded mx-2 text-center text-decoration-none'>
                        <i class='bi bi-pencil-fill'></i>
                    </a>
                    <a href='#' class='bg-danger text-white px-2 py-1 rounded mx-2 text-center text-decoration-none' onclick=\"event.preventDefault(); document.getElementById('delete-".$row->id."').submit();\">
                        <i class='bi bi-trash3-fill'></i>
                    </a>
                    <form id='delete-".$row->id."' method=\"POST\" action='".esc_html(admin_url('admin-post.php'))."' class=\"mb-3\">
                        <input type=\"hidden\" name=\"action\" value=\"certificate_check_admin_delete\">
                        ".wp_nonce_field('certificate_check_admin_delete', 'certificate_check_admin')."
                        <input type=\"hidden\" name=\"redirectToUrl\" value='".certificate_check_admin_view_pagename('')."'>
                        <input type=\"hidden\" name=\"id\" value='".$row->id."'>
                    </form>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>


<div class="text-center" style="margin-top: 8rem">
        Created By <a class="text-decoration-none" href="https://github.com/Mahmoud217TR" target="__blank"> MahmoudTR</a> at <a class="text-decoration-none" href="https://qit.company" target="__blank" style="color:#B9A500!important">QIT Company</a>.
</div>