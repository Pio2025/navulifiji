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

                <div class="alert alert-danger validation-summary-alert d-flex align-items-start gap-3 mb-4 d-none" id="clientValidationAlert">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <div>
                        <div class="validation-summary-title">Your application could not be submitted</div>
                        <p>Please review the field(s) highlighted in red below and correct the error(s) shown before submitting again.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm subscribe-card">
                    <div class="card-body p-4 p-lg-5">
                        <form method="post" enctype="multipart/form-data" action="<?= site_url('account/subscribe') ?>" id="subscribeForm" novalidate>
                            <?= csrf_field() ?>

                            <!-- Account Plan -->
                            <h4 class="subscribe-section-title">Account Plan</h4>
                            <p class="text-muted mb-1">Select the plan that best fits your school's needs and size. <a href="<?= site_url('pricing') ?>" target="_blank">See full plan comparison</a>.</p>
                            <p class="required-note mb-3"><span class="required-note-star">*</span> Required</p>

                            <?php
                                $packageType = old('package_type');
                                if ($packageType === null) {
                                    $packageType = (!empty($selected_package) && $selected_package === 'web_mobile') ? 'web_mobile' : 'web';
                                }
                            ?>
                            <div class="text-center mb-4">
                                <div class="package-toggle-wrap">
                                    <span class="package-toggle-label <?= $packageType !== 'web_mobile' ? 'active' : '' ?>" data-package="web">Web App</span>
                                    <label class="package-switch">
                                        <input type="checkbox" id="packageSwitch" <?= $packageType === 'web_mobile' ? 'checked' : '' ?>>
                                        <span class="package-switch-slider"></span>
                                    </label>
                                    <span class="package-toggle-label <?= $packageType === 'web_mobile' ? 'active' : '' ?>" data-package="web_mobile">Both Web &amp; Mobile App</span>
                                </div>
                            </div>
                            <input type="hidden" name="package_type" id="package_type_input" value="<?= esc($packageType) ?>">

                            <?php $billingCycle = old('billing_cycle', $old['billing_cycle'] ?? 'monthly'); ?>
                            <div class="text-center mb-4">
                                <ul class="nav billing-cycle-tabs">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link <?= $billingCycle !== 'annual' ? 'active' : '' ?>" data-billing-cycle="monthly">Monthly</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link <?= $billingCycle === 'annual' ? 'active' : '' ?>" data-billing-cycle="annual">
                                            Annual <span class="badge-save">Save <?= (int) $annual_discount_percent ?>%</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden" name="billing_cycle" id="billing_cycle_input" value="<?= esc($billingCycle) ?>">

                            <div class="row gy-3" id="accountTypeGroup">
                                <?php foreach ($plans as $i => $plan): ?>
                                    <?php
                                        $isChecked = isset($old['account_type'])
                                            ? ($old['account_type'] == $plan['plan_id'])
                                            : (!empty($selected_plan) ? ($selected_plan == $plan['plan_id']) : $i === 0);
                                        $isCustomQuote = $plan['plan_monthly_cost'] === null;
                                        $monthlyCostWeb = $isCustomQuote ? 0.0 : (float) $plan['plan_monthly_cost'];
                                        $monthlyCostBundle = $isCustomQuote ? 0.0 : (float) ($plan['plan_monthly_cost_web_n_mobile'] ?? $plan['plan_monthly_cost']);
                                        $annualCostWeb = (float) ($plan['plan_annual_cost'] ?? 0);
                                        $annualCostBundle = (float) ($plan['plan_annual_cost_web_n_mobile'] ?? 0);
                                        $annualMonthlyRateWeb = $monthlyCostWeb * (1 - $annual_discount_percent / 100);
                                        $annualMonthlyRateBundle = $monthlyCostBundle * (1 - $annual_discount_percent / 100);
                                        $isFreePlan = !$isCustomQuote && $monthlyCostWeb <= 0;
                                        $monthlyLabelWeb = $isCustomQuote ? 'Contact us for pricing' : ($isFreePlan ? 'Free — limited time & features' : 'FJD $' . number_format($monthlyCostWeb) . ' / month (VAT incl.)');
                                        $monthlyLabelBundle = $isCustomQuote ? 'Contact us for pricing' : ($isFreePlan ? 'Free — limited time & features' : 'FJD $' . number_format($monthlyCostBundle) . ' / month (VAT incl.)');
                                        $annualLabelWeb = $isCustomQuote ? 'Contact us for pricing' : ($isFreePlan ? 'Free — limited time & features' : 'FJD $' . number_format($annualMonthlyRateWeb, 2) . ' / month (VAT incl., ' . (int) $annual_discount_percent . '% off, billed annually)');
                                        $annualLabelBundle = $isCustomQuote ? 'Contact us for pricing' : ($isFreePlan ? 'Free — limited time & features' : 'FJD $' . number_format($annualMonthlyRateBundle, 2) . ' / month (VAT incl., ' . (int) $annual_discount_percent . '% off, billed annually)');
                                        $monthlyLabel = $packageType === 'web_mobile' ? $monthlyLabelBundle : $monthlyLabelWeb;
                                        $annualLabel = $packageType === 'web_mobile' ? $annualLabelBundle : $annualLabelWeb;
                                    ?>
                                    <div class="col-md-6 col-lg-3">
                                        <input type="radio" class="radio-card-input" name="account_type" required
                                               id="account_type_<?= $plan['plan_id'] ?>"
                                               data-monthly-cost-web="<?= $monthlyCostWeb ?>"
                                               data-monthly-cost-bundle="<?= $monthlyCostBundle ?>"
                                               data-annual-cost-web="<?= $annualCostWeb ?>"
                                               data-annual-cost-bundle="<?= $annualCostBundle ?>"
                                               data-is-free="<?= $isFreePlan ? '1' : '0' ?>"
                                               data-is-custom-quote="<?= $isCustomQuote ? '1' : '0' ?>"
                                               data-plan-name="<?= esc($plan['plan_name']) ?>"
                                               value="<?= $plan['plan_id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <label class="radio-card" for="account_type_<?= $plan['plan_id'] ?>">
                                            <span class="radio-card-title"><?= esc($plan['plan_name']) ?></span>
                                            <span class="radio-card-desc plan-price-desc"
                                                  data-monthly-label-web="<?= esc($monthlyLabelWeb) ?>"
                                                  data-monthly-label-bundle="<?= esc($monthlyLabelBundle) ?>"
                                                  data-annual-label-web="<?= esc($annualLabelWeb) ?>"
                                                  data-annual-label-bundle="<?= esc($annualLabelBundle) ?>">
                                                <?= $billingCycle === 'annual' ? $annualLabel : $monthlyLabel ?>
                                            </span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-danger mt-2 <?= (isset($validation) && $validation->hasError('account_type')) ? '' : 'd-none' ?>" id="accountTypeError">
                                <small><?= (isset($validation) && $validation->hasError('account_type')) ? $validation->getError('account_type') : '' ?></small>
                            </div>

                            <div class="alert alert-info billing-cycle-info mt-3 d-flex align-items-start gap-3" id="billingCycleInfo">
                                <i class="bi bi-info-circle-fill fs-4"></i>
                                <div id="billingCycleInfoText">Billed monthly. Switch to Annual and save <?= (int) $annual_discount_percent ?>%.</div>
                            </div>

                            <hr class="my-5">

                            <!-- School Detail -->
                            <h4 class="subscribe-section-title">School Detail</h4>

                            <label class="form-label mb-2 required">School category</label>
                            <div class="row gy-3 mb-4" id="schCategoryGroup">
                                <?php $categories = [1 => ['Pre-School', 'bi-balloon-fill'], 2 => ['Kindergarten', 'bi-palette-fill'], 3 => ['Primary', 'bi-book-fill'], 4 => ['Secondary', 'bi-mortarboard-fill']]; ?>
                                <?php foreach ($categories as $catId => [$catLabel, $catIcon]): ?>
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="radio-card-input" name="sch_category" id="sch_category_<?= $catId ?>" required
                                               value="<?= $catId ?>" <?= (isset($old['sch_category']) && $old['sch_category'] == $catId) ? 'checked' : '' ?>>
                                        <label class="radio-card text-center align-items-center" for="sch_category_<?= $catId ?>">
                                            <i class="bi <?= $catIcon ?> radio-card-icon"></i>
                                            <span class="radio-card-title"><?= $catLabel ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-danger mb-4 <?= (isset($validation) && $validation->hasError('sch_category')) ? '' : 'd-none' ?>" id="schCategoryError">
                                <small><?= (isset($validation) && $validation->hasError('sch_category')) ? $validation->getError('sch_category') : '' ?></small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">School Name</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('account_name')) ? 'is-invalid' : '' ?>"
                                       name="account_name" value="<?= old('account_name', $old['account_name'] ?? '') ?>" required>
                                <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('account_name')) ? $validation->getError('account_name') : '' ?></div>
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
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('province')) ? $validation->getError('province') : '' ?></div>
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
                                            <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('district')) ? $validation->getError('district') : '' ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">School Phone</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('phone')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('phone', $old['phone'] ?? '') ?>" placeholder="Enter school phone" name="phone" required>
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('phone')) ? $validation->getError('phone') : '' ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">School Address</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('address')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('address', $old['address'] ?? '') ?>" placeholder="Enter school address" name="address" required>
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('address')) ? $validation->getError('address') : '' ?></div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label required">School Motto</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('motto')) ? 'is-invalid' : '' ?>"
                                       value="<?= old('motto', $old['motto'] ?? '') ?>" placeholder="Enter school motto" name="motto" required>
                                <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('motto')) ? $validation->getError('motto') : '' ?></div>
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
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('fname')) ? $validation->getError('fname') : '' ?></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label required">Last Name</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('lname')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('lname', $old['lname'] ?? '') ?>" placeholder="Enter last name" name="lname" required>
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('lname')) ? $validation->getError('lname') : '' ?></div>
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
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('gender')) ? $validation->getError('gender') : '' ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Date of Birth</label>
                                    <input type="date" class="form-control <?= (isset($validation) && $validation->hasError('dob')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('dob', $old['dob'] ?? '') ?>" name="dob" min="1901-01-01" max="<?= date('Y-m-d') ?>" required>
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('dob')) ? $validation->getError('dob') : '' ?></div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('my_email')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('my_email', $old['my_email'] ?? '') ?>" placeholder="Enter your personal email" name="my_email" required>
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('my_email')) ? $validation->getError('my_email') : '' ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Phone</label>
                                    <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('my_phone')) ? 'is-invalid' : '' ?>"
                                           value="<?= old('my_phone', $old['my_phone'] ?? '') ?>" placeholder="Enter your personal phone" name="my_phone" required>
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('my_phone')) ? $validation->getError('my_phone') : '' ?></div>
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
                                    <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('province2')) ? $validation->getError('province2') : '' ?></div>
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
                                            <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('district2')) ? $validation->getError('district2') : '' ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Address</label>
                                <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('my_address')) ? 'is-invalid' : '' ?>"
                                       value="<?= old('my_address', $old['my_address'] ?? '') ?>" placeholder="Enter your personal address" name="my_address" required>
                                <div class="invalid-feedback"><?= (isset($validation) && $validation->hasError('my_address')) ? $validation->getError('my_address') : '' ?></div>
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
                                    <div class="invalid-feedback <?= (isset($validation) && $validation->hasError('password')) ? 'd-block' : '' ?>" id="passwordError"><?= (isset($validation) && $validation->hasError('password')) ? $validation->getError('password') : '' ?></div>
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
                                    <div class="invalid-feedback <?= (isset($validation) && $validation->hasError('re-type-password')) ? 'd-block' : '' ?>" id="reTypePasswordError"><?= (isset($validation) && $validation->hasError('re-type-password')) ? $validation->getError('re-type-password') : '' ?></div>
                                </div>
                            </div>

                            <button type="submit" class="btn-brand" name="submit_btn" id="subscribeSubmitBtn">Submit Application <i class="bi bi-arrow-right"></i></button>
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
            var pkg = $('#package_type_input').val();
            var pkgKey = pkg === 'web_mobile' ? 'bundle' : 'web';

            $('.plan-price-desc').each(function () {
                var $el = $(this);
                $el.text(cycle === 'annual' ? $el.data('annual-label-' + pkgKey) : $el.data('monthly-label-' + pkgKey));
            });

            var $selected = $('input[name="account_type"]:checked');
            var isFree = $selected.data('is-free') == 1;
            var isCustomQuote = $selected.data('is-custom-quote') == 1;
            var monthly = parseFloat($selected.data('monthly-cost-' + pkgKey)) || 0;
            var annual = parseFloat($selected.data('annual-cost-' + pkgKey)) || 0;
            var discountPercent = <?= (int) $annual_discount_percent ?>;
            var infoText;

            if (!$selected.length) {
                infoText = 'Select a plan above to see pricing details.';
            } else if (isCustomQuote) {
                infoText = 'The ' + $selected.data('plan-name') + ' plan is custom-priced for your school. Contact our sales team for a quote — this form cannot be submitted for this plan.';
            } else if (isFree) {
                infoText = 'The Free plan runs for one month and does not require billing.';
            } else if (cycle === 'annual') {
                var savings = (monthly * 12 - annual).toFixed(2);
                infoText = 'Billed annually — FJD $' + annual.toFixed(2) + ' / year. You save FJD $' + savings + ' (' + discountPercent + '%) compared to monthly billing.';
            } else {
                infoText = 'Billed monthly — FJD $' + monthly.toFixed(2) + ' / month. Switch to Annual and save ' + discountPercent + '%.';
            }

            $('#billingCycleInfoText').text(infoText);
            $('#billingCycleInfo').toggleClass('alert-warning', isCustomQuote).toggleClass('alert-info', !isCustomQuote);

            if (isCustomQuote) {
                $('#subscribeSubmitBtn').html('Contact Sales Team <i class="bi bi-arrow-right"></i>');
            } else {
                $('#subscribeSubmitBtn').html('Submit Application <i class="bi bi-arrow-right"></i>');
            }
        }

        $('.billing-cycle-tabs .nav-link').click(function () {
            $('.billing-cycle-tabs .nav-link').removeClass('active');
            $(this).addClass('active');
            $('#billing_cycle_input').val($(this).data('billing-cycle'));
            updateBillingUI();
        });

        $('#packageSwitch').change(function () {
            var pkg = this.checked ? 'web_mobile' : 'web';
            $('.package-toggle-label').removeClass('active');
            $('.package-toggle-label[data-package="' + pkg + '"]').addClass('active');
            $('#package_type_input').val(pkg);
            updateBillingUI();
        });

        $('.package-toggle-label').click(function () {
            var pkg = $(this).data('package');
            $('#packageSwitch').prop('checked', pkg === 'web_mobile').trigger('change');
        });

        $('input[name="account_type"]').change(updateBillingUI);

        updateBillingUI();

        // ================= Real-time client-side validation =================
        // Mirrors the backend rules in AccountController::subscribe(). Note:
        // email uniqueness (is_unique[users.email]) cannot be checked here and
        // is still only enforced server-side.
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        function setFieldError($input, $feedback, message) {
            $input.addClass('is-invalid');
            if (!$feedback || !$feedback.length) return;
            $feedback.text(message);
            if ($feedback.hasClass('invalid-feedback')) {
                $feedback.addClass('d-block');
            } else {
                $feedback.removeClass('d-none');
            }
        }

        function clearFieldError($input, $feedback) {
            $input.removeClass('is-invalid');
            if (!$feedback || !$feedback.length) return;
            $feedback.text('');
            var id = $feedback.attr('id');
            if (id === 'passwordError' || id === 'reTypePasswordError') {
                $feedback.removeClass('d-block');
            } else if (!$feedback.hasClass('invalid-feedback')) {
                $feedback.addClass('d-none');
            }
        }

        var textValidators = {
            account_name: { required: 'School name is required', minLength: [3, 'School name must be at least 3 characters'], maxLength: [100, 'School name cannot exceed 100 characters'] },
            fname: { required: 'First name is required', minLength: [3, 'First name must be at least 3 characters'], maxLength: [100, 'First name cannot exceed 100 characters'] },
            lname: { required: 'Last name is required', minLength: [3, 'Last name must be at least 3 characters'], maxLength: [100, 'Last name cannot exceed 100 characters'] },
            phone: { required: 'Phone number is required', exactLength: [7, 'Phone number must be exactly 7 digits'] },
            my_phone: { required: 'Phone number is required', exactLength: [7, 'Phone number must be exactly 7 digits'] },
            address: { required: 'Address is required', minLength: [5, 'Address must be at least 5 characters'], maxLength: [255, 'Address cannot exceed 255 characters'] },
            my_address: { required: 'Address is required', minLength: [5, 'Address must be at least 5 characters'], maxLength: [255, 'Address cannot exceed 255 characters'] },
            motto: { required: 'School motto is required', minLength: [3, 'School motto must be at least 3 characters'], maxLength: [500, 'School motto cannot exceed 500 characters'] }
        };

        function validateTextField(name) {
            var $input = $('[name="' + name + '"]');
            if (!$input.length) return true;
            var $feedback = $input.siblings('.invalid-feedback').first();
            var rules = textValidators[name];
            var val = $.trim($input.val());

            if (!val) {
                setFieldError($input, $feedback, rules.required);
                return false;
            }
            if (rules.minLength && val.length < rules.minLength[0]) {
                setFieldError($input, $feedback, rules.minLength[1]);
                return false;
            }
            if (rules.maxLength && val.length > rules.maxLength[0]) {
                setFieldError($input, $feedback, rules.maxLength[1]);
                return false;
            }
            if (rules.exactLength && val.length !== rules.exactLength[0]) {
                setFieldError($input, $feedback, rules.exactLength[1]);
                return false;
            }
            clearFieldError($input, $feedback);
            return true;
        }

        $.each(textValidators, function (name) {
            $(document).on('input blur', '[name="' + name + '"]', function () {
                validateTextField(name);
            });
        });

        var selectMessages = {
            province: 'Please select a province',
            province2: 'Please select a province',
            gender: 'Please select a gender',
            district: 'Please select a district',
            district2: 'Please select a district',
            dob: 'Please select dob'
        };

        function validateRequiredField(name) {
            var $input = $('[name="' + name + '"]');
            if (!$input.length) return true;
            var $feedback = $input.siblings('.invalid-feedback').first();
            if (!$input.val()) {
                setFieldError($input, $feedback, selectMessages[name]);
                return false;
            }
            clearFieldError($input, $feedback);
            return true;
        }

        $(document).on('change blur', '[name="province"], [name="province2"], [name="gender"], [name="dob"], [name="district"], [name="district2"]', function () {
            validateRequiredField($(this).attr('name'));
        });

        function validateEmail() {
            var $input = $('[name="my_email"]');
            var $feedback = $input.siblings('.invalid-feedback').first();
            var val = $.trim($input.val());
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!val) {
                setFieldError($input, $feedback, 'Email is required');
                return false;
            }
            if (!emailRegex.test(val)) {
                setFieldError($input, $feedback, 'Please enter a valid email address');
                return false;
            }
            clearFieldError($input, $feedback);
            return true;
        }
        $(document).on('input blur', '[name="my_email"]', validateEmail);

        function validateRadioGroup(name, groupId, errorId, message) {
            var checked = $('input[name="' + name + '"]:checked').length > 0;
            var $group = $('#' + groupId);
            var $error = $('#' + errorId);
            if (!checked) {
                $group.addClass('group-invalid');
                $error.removeClass('d-none').find('small').text(message);
                return false;
            }
            $group.removeClass('group-invalid');
            $error.addClass('d-none').find('small').text('');
            return true;
        }
        $(document).on('change', 'input[name="account_type"]', function () {
            validateRadioGroup('account_type', 'accountTypeGroup', 'accountTypeError', 'Please select an account type');
        });
        $(document).on('change', 'input[name="sch_category"]', function () {
            validateRadioGroup('sch_category', 'schCategoryGroup', 'schCategoryError', 'Please select school category');
        });

        function validatePassword() {
            var $input = $('#password');
            var $feedback = $('#passwordError');
            var val = $input.val();
            if (!val) {
                setFieldError($input, $feedback, 'Password is required');
                return false;
            }
            if (val.length < 8) {
                setFieldError($input, $feedback, 'Password must be at least 8 characters');
                return false;
            }
            if (val.length > 32) {
                setFieldError($input, $feedback, 'Password cannot exceed 32 characters');
                return false;
            }
            if (!passwordRegex.test(val)) {
                setFieldError($input, $feedback, 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&)');
                return false;
            }
            clearFieldError($input, $feedback);
            if ($('#re-type-password').val()) {
                validateRetypePassword();
            }
            return true;
        }

        function validateRetypePassword() {
            var $input = $('#re-type-password');
            var $feedback = $('#reTypePasswordError');
            var val = $input.val();
            if (!val) {
                setFieldError($input, $feedback, 'Please re-type your password');
                return false;
            }
            if (val !== $('#password').val()) {
                setFieldError($input, $feedback, 'Passwords do not match');
                return false;
            }
            clearFieldError($input, $feedback);
            return true;
        }

        $(document).on('input blur', '#password', validatePassword);
        $(document).on('input blur', '#re-type-password', validateRetypePassword);

        $('#subscribeForm').on('submit', function (e) {
            var $selectedPlan = $('input[name="account_type"]:checked');
            if ($selectedPlan.length && $selectedPlan.data('is-custom-quote') == 1) {
                e.preventDefault();
                window.location.href = "<?= site_url('contact') ?>";
                return;
            }

            var valid = true;

            $.each(textValidators, function (name) {
                if (!validateTextField(name)) valid = false;
            });

            ['province', 'province2', 'gender', 'dob'].forEach(function (name) {
                if (!validateRequiredField(name)) valid = false;
            });
            if ($('[name="district"]').length && !validateRequiredField('district')) valid = false;
            if ($('[name="district2"]').length && !validateRequiredField('district2')) valid = false;

            if (!validateEmail()) valid = false;
            if (!validateRadioGroup('account_type', 'accountTypeGroup', 'accountTypeError', 'Please select an account type')) valid = false;
            if (!validateRadioGroup('sch_category', 'schCategoryGroup', 'schCategoryError', 'Please select school category')) valid = false;
            if (!validatePassword()) valid = false;
            if (!validateRetypePassword()) valid = false;

            if (!valid) {
                e.preventDefault();
                $('#clientValidationAlert').removeClass('d-none');
                var $firstInvalid = $('.is-invalid, .group-invalid').first();
                if ($firstInvalid.length) {
                    $('html, body').animate({ scrollTop: $firstInvalid.offset().top - 120 }, 400);
                }
            } else {
                $('#clientValidationAlert').addClass('d-none');
            }
        });
    });
</script>
