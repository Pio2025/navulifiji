<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Get Started</span>
        <h1 class="mt-3 text-white">Register your school with Navuli</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Complete the form below to create your school's master account. Every application is reviewed to make sure your school gets the best experience from day one.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation) && $validation->getErrors()): ?>
                    <div class="alert alert-danger validation-summary-alert d-flex align-items-start gap-3 mb-4" data-aos="fade-up">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                        <div>
                            <div class="validation-summary-title">Your application could not be submitted</div>
                            <p>Please review the field(s) highlighted in red below and correct the error(s) shown before submitting again.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm subscribe-card">
                    <div class="card-body p-4 p-lg-5">
                        <form method="post" enctype="multipart/form-data" action="<?= site_url('account/subscribe') ?>">
                            <?= csrf_field() ?>

                            <!-- Account Plan -->
                            <h4 class="subscribe-section-title">Account Plan</h4>
                            <p class="text-muted mb-1">Select the plan that best fits your school's needs and size. <a href="<?= site_url('pricing') ?>" target="_blank">See full plan comparison</a>.</p>
                            <p class="required-note mb-3"><span class="required-note-star">*</span> Required</p>

                            <?php $billingCycle = old('billing_cycle', $old['billing_cycle'] ?? 'monthly'); ?>
                            <ul class="nav billing-cycle-tabs mb-4">
                                <li class="nav-item">
                                    <button type="button" class="nav-link <?= $billingCycle !== 'annual' ? 'active' : '' ?>" data-billing-cycle="monthly">Monthly</button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link <?= $billingCycle === 'annual' ? 'active' : '' ?>" data-billing-cycle="annual">
                                        Annual <span class="badge-save">Save <?= (int) $annual_discount_percent ?>%</span>
                                    </button>
                                </li>
                            </ul>
                            <input type="hidden" name="billing_cycle" id="billing_cycle_input" value="<?= esc($billingCycle) ?>">

                            <div class="row gy-3">
                                <?php foreach ($plans as $i => $plan): ?>
                                    <?php
                                        $isChecked = isset($old['account_type'])
                                            ? ($old['account_type'] == $plan['plan_id'])
                                            : (!empty($selected_plan) ? ($selected_plan == $plan['plan_id']) : $i === 0);
                                        $monthlyCost = (float) $plan['plan_monthly_cost'];
                                        $annualCost = (float) ($plan['plan_annual_cost'] ?? 0);
                                        $isFreePlan = $monthlyCost <= 0;
                                        $monthlyLabel = $isFreePlan ? 'Free — limited time & features' : 'FJD $' . number_format($monthlyCost) . ' / month (VAT incl.)';
                                        $annualLabel = $isFreePlan ? 'Free — limited time & features' : 'FJD $' . number_format($annualCost) . ' / year (VAT incl., ' . (int) $annual_discount_percent . '% off)';
                                    ?>
                                    <div class="col-md-4">
                                        <input type="radio" class="radio-card-input" name="account_type" required
                                               id="account_type_<?= $plan['plan_id'] ?>"
                                               data-monthly-cost="<?= $monthlyCost ?>"
                                               data-annual-cost="<?= $annualCost ?>"
                                               data-is-free="<?= $isFreePlan ? '1' : '0' ?>"
                                               value="<?= $plan['plan_id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <label class="radio-card" for="account_type_<?= $plan['plan_id'] ?>">
                                            <span class="radio-card-title"><?= esc($plan['plan_name']) ?></span>
                                            <span class="radio-card-desc plan-price-desc"
                                                  data-monthly-label="<?= esc($monthlyLabel) ?>"
                                                  data-annual-label="<?= esc($annualLabel) ?>">
                                                <?= $billingCycle === 'annual' ? $annualLabel : $monthlyLabel ?>
                                            </span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('account_type')): ?>
                                <div class="text-danger mt-2"><small><?= $validation->getError('account_type') ?></small></div>
                            <?php endif; ?>

                            <div class="alert alert-info billing-cycle-info mt-3 d-flex align-items-start gap-3" id="billingCycleInfo">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <div id="billingCycleInfoText">Billed monthly. Switch to Annual and save <?= (int) $annual_discount_percent ?>%.</div>
                            </div>

                            <hr class="my-5">

                            <!-- School Detail -->
                            <h4 class="subscribe-section-title">School Detail</h4>

                            <label class="form-label mb-2 required">School category</label>
                            <div class="row gy-3 mb-4">
                                <?php $categories = [1 => 'Pre-School', 2 => 'Kindergarten', 3 => 'Primary', 4 => 'Secondary']; ?>
                                <?php foreach ($categories as $catId => $catLabel): ?>
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="radio-card-input" name="sch_category" id="sch_category_<?= $catId ?>" required
                                               value="<?= $catId ?>" <?= (isset($old['sch_category']) && $old['sch_category'] == $catId) ? 'checked' : '' ?>>
                                        <label class="radio-card text-center align-items-center" for="sch_category_<?= $catId ?>">
                                            <span class="radio-card-title"><?= $catLabel ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('sch_category')): ?>
                                <div class="text-danger mb-4"><small><?= $validation->getError('sch_category') ?></small></div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="form-label required">School Name</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('account_name')) ? 'is-invalid' : '' ?>"
                                       name="account_name" value="<?= old('account_name', $old['account_name'] ?? '') ?>" required>
                                <?php if (isset($validation) && $validation->hasError('account_name')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('account_name') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Select Province</label>
                                    <select class="form-select selectProvince <?= (isset($validation) && $validation->hasError('province')) ? 'is-invalid' : '' ?>" name="province" required>
                                        <option value="">Select ...</option>
                                        <?php foreach ($province as $row): ?>
                                            <?php if ($row['province_name'] != 'Foreign Citizen'): ?>
                                                <option value="<?= $row['province_id'] ?>" <?= (old('province', $old['province'] ?? '') == $row['province_id']) ? 'selected' : '' ?>>
                                                    <?= $row['province_name'] ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($validation) && $validation->hasError('province')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('province') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <span id="loader" style="display:none;"><img src="<?= base_url('resources/ajax-loader/ajax-loader-3.gif') ?>"></span>
                                    <div class="response">
                                        <?php if (!empty($old['province'])): ?>
                                            <label class="form-label required">Select District</label>
                                            <select class="form-select <?= (isset($validation) && $validation->hasError('district')) ? 'is-invalid' : '' ?>" name="district" required>
                                                <option value="">Select ...</option>
                                                <?php foreach ($provinceDistrict as $row): ?>
                                                    <option value="<?= esc($row['district_id']) ?>" <?= (old('district', $old['district'] ?? '') == $row['district_id']) ? 'selected' : '' ?>>
                                                        <?= esc($row['district_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if (isset($validation) && $validation->hasError('district')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('district') ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">School Phone</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('phone')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('phone', $old['phone'] ?? '') ?>" placeholder="Enter school phone" name="phone" required>
                                    <?php if (isset($validation) && $validation->hasError('phone')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('phone') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">School Address</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('address')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('address', $old['address'] ?? '') ?>" placeholder="Enter school address" name="address" required>
                                    <?php if (isset($validation) && $validation->hasError('address')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('address') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label required">School Motto</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('motto')) ? 'is-invalid' : '' ?>"
                                       value="<?= old('motto', $old['motto'] ?? '') ?>" placeholder="Enter school motto" name="motto" required>
                                <?php if (isset($validation) && $validation->hasError('motto')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('motto') ?></div>
                                <?php endif; ?>
                            </div>

                            <hr class="my-5">

                            <!-- Personal Detail -->
                            <h4 class="subscribe-section-title">Personal Detail</h4>
                            <div class="alert alert-info d-flex align-items-start gap-3 mb-4">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <div><strong>Note:</strong> Your personalized administrator account will be created, granting you full access to configure your school's profile, manage user permissions, and oversee all operations. Access your dedicated dashboard with your secure credentials to begin.</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label required">First Name</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('fname')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('fname', $old['fname'] ?? '') ?>" placeholder="Enter first name" name="fname" required>
                                    <?php if (isset($validation) && $validation->hasError('fname')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('fname') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Last Name</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('lname')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('lname', $old['lname'] ?? '') ?>" placeholder="Enter last name" name="lname" required>
                                    <?php if (isset($validation) && $validation->hasError('lname')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('lname') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Other Name(s)</label>
                                    <input type="text" class="form-control" value="<?= old('oname', $old['oname'] ?? '') ?>" placeholder="Enter other name(s)" name="oname">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Gender</label>
                                    <select class="form-select <?= (isset($validation) && $validation->hasError('gender')) ? 'is-invalid' : '' ?>" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?= (old('gender', $old['gender'] ?? '') == 'Male') ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= (old('gender', $old['gender'] ?? '') == 'Female') ? 'selected' : '' ?>>Female</option>
                                    </select>
                                    <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('gender') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Date of Birth</label>
                                    <input type="date" class="form-control <?= (isset($validation) && $validation->hasError('dob')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('dob', $old['dob'] ?? '') ?>" name="dob" min="1901-01-01" max="<?= date('Y-m-d') ?>" required>
                                    <?php if (isset($validation) && $validation->hasError('dob')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('dob') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('my_email')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('my_email', $old['my_email'] ?? '') ?>" placeholder="Enter your personal email" name="my_email" required>
                                    <?php if (isset($validation) && $validation->hasError('my_email')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('my_email') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Phone</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('my_phone')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('my_phone', $old['my_phone'] ?? '') ?>" placeholder="Enter your personal phone" name="my_phone" required>
                                    <?php if (isset($validation) && $validation->hasError('my_phone')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('my_phone') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Select Province</label>
                                    <select class="form-select selectProvince2 <?= (isset($validation) && $validation->hasError('province2')) ? 'is-invalid' : '' ?>" name="province2" required>
                                        <option value="">Select ...</option>
                                        <?php foreach ($province as $row): ?>
                                            <option value="<?= $row['province_id'] ?>" <?= (old('province2', $old['province2'] ?? '') == $row['province_id']) ? 'selected' : '' ?>>
                                                <?= $row['province_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($validation) && $validation->hasError('province2')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('province2') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <span id="loader2" style="display:none;"><img src="<?= base_url('resources/ajax-loader/ajax-loader-3.gif') ?>"></span>
                                    <div class="response2">
                                        <?php if (!empty($old['province2'])): ?>
                                            <label class="form-label required">Select District</label>
                                            <select class="form-select <?= (isset($validation) && $validation->hasError('district2')) ? 'is-invalid' : '' ?>" name="district2" required>
                                                <option value="">Select ...</option>
                                                <?php foreach ($provinceDistrict2 as $row): ?>
                                                    <option value="<?= esc($row['district_id']) ?>" <?= (old('district2', $old['district2'] ?? '') == $row['district_id']) ? 'selected' : '' ?>>
                                                        <?= esc($row['district_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if (isset($validation) && $validation->hasError('district2')): ?>
                                                <div class="invalid-feedback"><?= $validation->getError('district2') ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Address</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('my_address')) ? 'is-invalid' : '' ?>"
                                       value="<?= old('my_address', $old['my_address'] ?? '') ?>" placeholder="Enter your personal address" name="my_address" required>
                                <?php if (isset($validation) && $validation->hasError('my_address')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('my_address') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="alert alert-info mb-4">
                                <strong>Password Requirements:</strong>
                                <ul class="small mb-0 mt-2">
                                    <li>Minimum 8 characters, maximum 32 characters</li>
                                    <li>At least one uppercase letter (A-Z)</li>
                                    <li>At least one lowercase letter (a-z)</li>
                                    <li>At least one number (0-9)</li>
                                    <li>At least one special character (@$!%*?&)</li>
                                </ul>
                            </div>

                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label class="form-label required">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                                               name="password" id="password" placeholder="Enter password" required>
                                        <span class="input-group-text toggle-password" data-target="password" style="cursor:pointer;">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                    </div>
                                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                                        <div class="text-danger mt-1"><small><?= $validation->getError('password') ?></small></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Re-type Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control <?= (isset($validation) && $validation->hasError('re-type-password')) ? 'is-invalid' : '' ?>"
                                               name="re-type-password" id="re-type-password" placeholder="Re-type password" required>
                                        <span class="input-group-text toggle-password" data-target="re-type-password" style="cursor:pointer;">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                    </div>
                                    <?php if (isset($validation) && $validation->hasError('re-type-password')): ?>
                                        <div class="text-danger mt-1"><small><?= $validation->getError('re-type-password') ?></small></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn-brand" name="submit_btn">Submit Application <i class="bi bi-arrow-right"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $(".selectProvince").change(function () {
            var id = $(this).val();
            if (!id) { $(".response").html(''); return; }

            $.ajax({
                url: "<?= base_url('district/getDistrictByProvince') ?>",
                type: 'POST',
                data: { id: id, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                dataType: 'json',
                beforeSend: function () { $("#loader").show(); $(".response").hide(); },
                success: function (response) {
                    $('.response').empty();
                    $(".response").show();
                    if (response.success) {
                        $('.response').html(response.html);
                    } else {
                        $('.response').html('<label class="form-label">Select District</label><div class="alert alert-danger">' + response.error + '</div>');
                    }
                },
                error: function () {
                    $('.response').show().html('<label class="form-label">Select District</label><div class="alert alert-danger">Error loading districts</div>');
                },
                complete: function () { $("#loader").hide(); }
            });
        });

        $(".selectProvince2").change(function () {
            var id = $(this).val();
            if (!id) { $(".response2").html(''); return; }

            $.ajax({
                url: "<?= base_url('district/getDistrictByProvince2') ?>",
                type: 'POST',
                data: { id: id, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                dataType: 'json',
                beforeSend: function () { $("#loader2").show(); $(".response2").hide(); },
                success: function (response) {
                    $('.response2').empty();
                    $(".response2").show();
                    if (response.success) {
                        $('.response2').html(response.html);
                    } else {
                        $('.response2').html('<label class="form-label">Select District</label><div class="alert alert-danger">' + response.error + '</div>');
                    }
                },
                error: function () {
                    $('.response2').show().html('<label class="form-label">Select District</label><div class="alert alert-danger">Error loading districts</div>');
                },
                complete: function () { $("#loader2").hide(); }
            });
        });

        $('.toggle-password').click(function () {
            var targetId = $(this).data('target');
            var input = $('#' + targetId);
            var icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });

        function updateBillingUI() {
            var cycle = $('#billing_cycle_input').val();

            $('.plan-price-desc').each(function () {
                var $el = $(this);
                $el.text(cycle === 'annual' ? $el.data('annual-label') : $el.data('monthly-label'));
            });

            var $selected = $('input[name="account_type"]:checked');
            var isFree = $selected.data('is-free') == 1;
            var monthly = parseFloat($selected.data('monthly-cost')) || 0;
            var annual = parseFloat($selected.data('annual-cost')) || 0;
            var discountPercent = <?= (int) $annual_discount_percent ?>;
            var infoText;

            if (!$selected.length) {
                infoText = 'Select a plan above to see pricing details.';
            } else if (isFree) {
                infoText = 'The Free plan runs for one month and does not require billing.';
            } else if (cycle === 'annual') {
                var savings = (monthly * 12 - annual).toFixed(2);
                infoText = 'Billed annually — FJD $' + annual.toFixed(2) + ' / year. You save FJD $' + savings + ' (' + discountPercent + '%) compared to monthly billing.';
            } else {
                infoText = 'Billed monthly — FJD $' + monthly.toFixed(2) + ' / month. Switch to Annual and save ' + discountPercent + '%.';
            }

            $('#billingCycleInfoText').text(infoText);
        }

        $('.billing-cycle-tabs .nav-link').click(function () {
            $('.billing-cycle-tabs .nav-link').removeClass('active');
            $(this).addClass('active');
            $('#billing_cycle_input').val($(this).data('billing-cycle'));
            updateBillingUI();
        });

        $('input[name="account_type"]').change(updateBillingUI);

        updateBillingUI();
    });
</script>
