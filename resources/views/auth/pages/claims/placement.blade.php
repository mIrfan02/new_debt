 <x-front.card>
     <form action="{{ route('placements.store') }}" method="POST">
         @csrf

         @if ($errors->any())
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                 <strong>Error!</strong> Please fix the following issues:
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>
         @endif



         <input type="hidden" name="claim_id" id="claim_id" value="{{ $data->id }}">

         <!-- Placement Details -->
         <h4>Placements</h4>
         <div class="row">
             <div class="mb-3 col-md-6">
                 <label for="placement_date" class="form-label">Placement Date:</label>
                 <input type="date" name="placement_date" id="placement_date" class="form-control" value="{{ $data->placement->placement_date ?? ' ' }}">
             </div>

             <div class="mb-3 col-md-6">
                 <label for="contingency_method" class="form-label">Contingency Method:</label>
                 <select name="contigency_method" id="contingency_method" class="form-control">
                    <option value="none" {{ (isset($data->placement->contigency_method) && $data->placement->contigency_method == 'none') ? 'selected' : '' }}>None</option>
                    <option value="sliding_scale" {{ (isset($data->placement->contigency_method) && $data->placement->contigency_method == 'sliding_scale') ? 'selected' : '' }}>Sliding scale</option>
                    <option value="fixed_rate" {{ (isset($data->placement->contigency_method) && $data->placement->contigency_method == 'fixed_rate') ? 'selected' : '' }}>Fixed rate</option>
                    <option value="flat_fee" {{ (isset($data->placement->contigency_method) && $data->placement->contigency_method == 'flat_fee') ? 'selected' : '' }}>Flat Fee</option>

                </select>
             </div>


         </div>

         <div class="allocation-container" id="sliding-scale">
             <!-- Default row -->
             <div class="allocation-row row">
                 <div class="col-md-5">
                     <label>Percentage:</label>
                     <input type="number" class="percentage-field" step="0.01" name="sliding_scale[0][percentage]">
                 </div>
                 <div class="col-md-5">
                     <label>Amount:</label>
                     <input type="number" class="amount-field" name="sliding_scale[0][amount]">
                 </div>
             </div>
         </div>
         <button class="add-row-btn" id="sliding-btn">+ Add Row</button>



         {{-- <div class="row">
             <div class="mb-3 col-md-12" id="method_rate">
                 <label for="method_rate" class="form-label">Method Rate:</label>
                 <input type="text" name="method_rate" id="method_rate_input" class="form-control">
                 <p id="percentage_display"></p>
             </div>
         </div> --}}

         <div class="row">
             <div class="mb-3 col-md-12" id="flat_fee">
                 <label for="flat_fee" class="form-label">Flat Fee:</label>
                 <input type="number" name="method_rate[]" id="flat_fee_input" class="form-control">
                 <p id="percentage_display"></p>

             </div>
         </div>

         <div class="row">
             <div class="mb-3 col-md-12" id="fixed_rate">
                 <label for="fixed_rate" class="form-label">Fixed Rate:</label>
                 <input type="number" name="method_rate[]" id="fixed_rate_input" step="0.1" class="form-control">
                 <p id="percentage_fixed_rate"></p>

             </div>
         </div>

         <input type="hidden" name="method_rate" id="method_rate_input">

         {{-- <div class="row">
            <div class="mb-3 col-md-12" id="full_amount">
                <label for="full_amount" class="form-label">Enter Full Amount:</label>
                <input type="number" name="method_rate" id="full_amount" class="form-control">


            </div>
        </div> --}}



         <div class="row">



             <div class="mb-3 col-md-6">
                 <label for="interest_start_date" class="form-label">Interest Start Date:</label>
                 <input type="date" name="interest_start_date" value="{{ $data->placement->interest_start_date }}" id="interest_start_date" class="form-control">
             </div>


             <div class="mb-3 col-md-6">
                 <label for="allocation_method" class="form-label">Allocation Method:</label>
                 <select name="allocation_method" id="allocation_method" class="form-control">
                    <option value="none" {{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'none') ? 'selected' : '' }}>None</option>
                     <option value="CIP" {{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'CIP') ? 'selected' : '' }}>CIP</option>
                     <option value="CPI"{{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'CPI') ? 'selected' : '' }}>CPI</option>
                     <option value="PCI"{{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'PCI') ? 'selected' : '' }}>PCI</option>
                     <option value="PIC"{{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'PIC') ? 'selected' : '' }}>PIC</option>
                     <option value="IPC"{{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'IPC') ? 'selected' : '' }}>IPC</option>
                     <option value="ICP"{{ (isset($data->placement->allocation_method) && $data->placement->allocation_method == 'ICP') ? 'selected' : '' }}>ICP</option>
                 </select>
             </div>





         </div>

         <div class="row">



             <div class="mb-3 col-md-6">
                 <label for="interest_rate" class="form-label">Interest Rate:</label>
                 <input type="number" name="interest_rate" id="interest_rate" value="{{ $data->placement->interest_rate }}" class="form-control" min="0"
                     max="100" step="0.01" oninput="updateInterestRate()">
                 <span id="interestRateDisplay"></span>

                 <script>
                     function updateInterestRate() {
                         var inputElement = document.getElementById("interest_rate");
                         var displayElement = document.getElementById("interestRateDisplay");
                         var value = inputElement.valueAsNumber;

                         // Check if the entered value is a valid number
                         if (!isNaN(value)) {
                             // Ensure the value is within the specified range
                             value = Math.min(value, 100);
                             inputElement.value = value; // Update the input field in case it was modified
                             displayElement.textContent = value + "%";
                         } else {
                             displayElement.textContent = "Invalid input";
                         }
                     }
                 </script>
             </div>



             <div class="mb-3 col-md-6">
                 <label for="debt_type" class="form-label">Debt Type:</label>
                 <select name="debt_type" id="debt_type" class="form-control">
                    <option value="none" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'none') ? 'selected' : '' }}>None</option>
                    <option value="Ambulance" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'Ambulance') ? 'selected' : '' }}>Ambulance</option>
                    <option value="Auto lease" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'Auto lease') ? 'selected' : '' }}>Auto Lease</option>
                    <option value="Auto loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'Auto loan') ? 'selected' : '' }}>Auto Loan</option>
                    <option value="car loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'car loan') ? 'selected' : '' }}>Car Loan</option>
                    <option value="charge card" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'charge card') ? 'selected' : '' }}>Charge Card</option>
                    <option value="child support" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'child support') ? 'selected' : '' }}>Child Support</option>
                    <option value="commercial" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'commercial') ? 'selected' : '' }}>Commercial</option>
                    <option value="consumer" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'consumer') ? 'selected' : '' }}>Consumer</option>
                    <option value="credit card" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'credit card') ? 'selected' : '' }}>Credit Card</option>
                    <option value="dental" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'dental') ? 'selected' : '' }}>Dental</option>
                    <option value="doctor" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'doctor') ? 'selected' : '' }}>Doctor</option>
                    <option value="home loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'home loan') ? 'selected' : '' }}>Home Loan</option>
                    <option value="hospital" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'hospital') ? 'selected' : '' }}>Hospital</option>
                    <option value="legal services" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'legal services') ? 'selected' : '' }}>Legal Services</option>
                    <option value="loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'loan') ? 'selected' : '' }}>Loan</option>
                    <option value="medical" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'medical') ? 'selected' : '' }}>Medical</option>
                    <option value="rent" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'rent') ? 'selected' : '' }}>Rent</option>
                    <option value="secured loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'secured loan') ? 'selected' : '' }}>Secured Loan</option>
                    <option value="services rendered" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'services rendered') ? 'selected' : '' }}>Services Rendered</option>
                    <option value="spousal support" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'spousal support') ? 'selected' : '' }}>Spousal Support</option>
                    <option value="student loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'student loan') ? 'selected' : '' }}>Student Loan</option>
                    <option value="unsecured loan" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'unsecured loan') ? 'selected' : '' }}>Unsecured Loan</option>
                    <option value="utility" {{ (isset($data->placement->debt_type) && $data->placement->debt_type == 'utility') ? 'selected' : '' }}>Utility</option>
                </select>
             </div>


         </div>

         <div class="row">

             {{-- <input type="hidden" name="debtor_id" id="debtor_id" class="form-control" value="{{ $debtorId }}"> --}}


         </div>


         <!-- Placement Components Details -->
         <h4>Placement Components</h4>

         <!-- Principal -->
         <div class="row">
             <input type="hidden" name="name[]" value="principal">
             <div class="mb-3 col-md-2">
                 <label for="principal_amount" class="form-label"> Amount:</label>
                 <input type="number" name="amount[]" id="principal_amount" class="form-control" value="{{$data->placement_amount }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="principal_rate" class="form-label"> Rate:</label>
                 <input type="number" name="rate[]" id="principal_rate" class="form-control" min="0" max="100" step="0.01" value="{{ $data->placement->placementComponent->where('name', 'principal')->first()->rate ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="principal_date" class="form-label"> Date:</label>
                 <input type="date" name="date[]" id="principal_date" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'principal')->first()->date ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="principal_comments" class="form-label"> Comments:</label>
                 <input type="text" name="comments[]" id="principal_comments" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'principal')->first()->comments ?? '' }}">
             </div>
         </div>

         <!-- Cost -->
         <div class="row">
             <input type="hidden" name="name[]" value="cost">
             <div class="mb-3 col-md-2">
                 <label for="cost_amount" class="form-label"> Amount:</label>
                 <input type="number" name="amount[]" id="cost_amount" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'cost')->first()->amount ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="cost_rate" class="form-label"> Rate:</label>
                 <input type="number" name="rate[]" id="cost_rate" class="form-control" min="0" max="100" step="0.01" value="{{ $data->placement->placementComponent->where('name', 'cost')->first()->rate ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="cost_date" class="form-label"> Date:</label>
                 <input type="date" name="date[]" id="cost_date" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'cost')->first()->date ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="cost_comments" class="form-label"> Comments:</label>
                 <input type="text" name="comments[]" id="cost_comments" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'cost')->first()->comments ?? '' }}">
             </div>
         </div>

         <!-- Attorney -->
         <div class="row">
             <input type="hidden" name="name[]" value="attorney">
             <div class="mb-3 col-md-2">
                 <label for="attorney_amount" class="form-label"> Amount:</label>
                 <input type="number" name="amount[]" id="attorney_amount" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'attorney')->first()->amount ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="attorney_rate" class="form-label"> Rate:</label>
                 <input type="number" name="rate[]" id="attorney_rate" class="form-control" min="0" max="100" step="0.01" value="{{ $data->placement->placementComponent->where('name', 'attorney')->first()->rate ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="attorney_date" class="form-label"> Date:</label>
                 <input type="date" name="date[]" id="attorney_date" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'attorney')->first()->date ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="attorney_comments" class="form-label"> Comments:</label>
                 <input type="text" name="comments[]" id="attorney_comments" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'attorney')->first()->comments ?? '' }}">
             </div>
         </div>

         <!-- Interest -->
         <div class="row">
             <input type="hidden" name="name[]" value="interest">
             <div class="mb-3 col-md-2">
                 <label for="interest_amount" class="form-label"> Amount:</label>
                 <input type="number" name="amount[]" id="interest_amount" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'interest')->first()->amount ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="interest_rate" class="form-label"> Rate:</label>
                 <input type="number" name="rate[]" id="interest_rate" class="form-control" min="0" max="100" step="0.01" value="{{ $data->placement->placementComponent->where('name', 'interest')->first()->rate ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="interest_date" class="form-label"> Date:</label>
                 <input type="date" name="date[]" id="interest_date" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'interest')->first()->date ?? '' }}">
             </div>
             <div class="mb-3 col-md-2">
                 <label for="interest_comments" class="form-label"> Comments:</label>
                 <input type="text" name="comments[]" id="interest_comments" class="form-control" value="{{ $data->placement->placementComponent->where('name', 'interest')->first()->comments ?? '' }}">
             </div>
         </div>

         <h4>Additions to Placement</h4>
         <div id="additionFieldsContainer">
             <!-- Initial Row -->
             <div class="row" data-row-index="1">
                 <div class="mb-3 col-md-2">
                     <label for="principal_name" class="form-label"> Name:</label>
                     <input type="text" name="name[]" class="form-control" value="Addition#01" readonly>
                 </div>
                 <div class="mb-3 col-md-2">
                     <label for="principal_amount" class="form-label"> Amount:</label>
                     <input type="number" name="amount[]" class="form-control">
                 </div>
                 <div class="mb-3 col-md-2">
                     <label for="principal_rate" class="form-label"> Rate:</label>
                     <input type="number" name="rate[]" class="form-control" min="0" max="100"
                         step="0.01">
                 </div>
                 <div class="mb-3 col-md-2">
                     <label for="principal_date" class="form-label"> Date:</label>
                     <input type="date" name="date[]" class="form-control">
                 </div>
                 <div class="mb-3 col-md-2">
                     <label for="principal_comments" class="form-label"> Comments:</label>
                     <input type="text" name="comments[]" class="form-control" />
                 </div>

             </div>
         </div>

         <!-- Plus Button -->
         <div class="row">
             <div class="col-md-6">
                 <button type="button" id="addAdditionField" class="btn btn-primary mb-3">
                     <i class="fa fa-plus"></i> Add
                 </button>

             </div>

             <div class="col-md-6">
                 <button type="submit" class="btn btn-primary">Submit</button>

             </div>
         </div>
     </form>
 </x-front.card>

 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
 <script>
     let additionFieldCount = 1;

     document.getElementById("addAdditionField").addEventListener("click", function() {
         additionFieldCount++;

         // Pad the additionFieldCount to ensure it has two digits
         const paddedCount = ("0" + additionFieldCount).slice(-2);

         const additionFields = document.createElement("div");
         additionFields.innerHTML = `
        <div class="row">
            <div class="mb-3 col-md-2">
                <label for="principal_name_${paddedCount}" class="form-label">Name:</label>
                <input type="text" name="name[]" id="principal_name_${paddedCount}" class="form-control" value="Addition#${paddedCount}" readonly>
            </div>
            <div class="mb-3 col-md-2">
                <label for="principal_amount_${paddedCount}" class="form-label">Amount:</label>
                <input type="number" name="amount[]" id="principal_amount_${paddedCount}" class="form-control">
            </div>
            <div class="mb-3 col-md-2">
                <label for="principal_rate_${paddedCount}" class="form-label">Rate:</label>
                <input type="number" name="rate[]" id="principal_rate_${paddedCount}" class="form-control" min="0" max="100" step="0.01">
            </div>
            <div class="mb-3 col-md-2">
                <label for="principal_date_${paddedCount}" class="form-label">Date:</label>
                <input type="date" name="date[]" id="principal_date_${paddedCount}" class="form-control">
            </div>
            <div class="mb-3 col-md-2">
                <label for="principal_comments_${paddedCount}" class="form-label">Comments:</label>
                <input type="text" name="comments[]" id="principal_comments_${paddedCount}" class="form-control" />
            </div>

        </div>
    `;

         document.getElementById("additionFieldsContainer").appendChild(additionFields);
     });
 </script>
 <script>
     function updateRate(rateFieldId) {
         var inputElement = document.getElementById(rateFieldId);
         var value = inputElement.valueAsNumber;

         // Check if the entered value is a valid number
         if (!isNaN(value)) {
             // Ensure the value is within the specified range
             value = Math.min(value, 100);
             inputElement.value = value; // Update the input field in case it was modified
         }
     }

     var rateFields = document.querySelectorAll('input[name^="rate[]"]');

     rateFields.forEach(function(field) {
         field.addEventListener('input', function() {
             updateRate(field.id);
         });
     });
 </script>
 <script>
     $(document).ready(function() {
         $('#method_rate').hide();

         $('#contingency_method').change(function() {
             var selectedOption = $(this).val();
             if (selectedOption == 'hourly_rate') {
                 $('#method_rate').show();
             } else {
                 $('#method_rate').hide();
             }
         });
     });

     $(document).ready(function() {
         // Initially hide the Method Rate field
         $('#sliding-scale').hide();
         $('#sliding-btn').hide();


         // Show/hide the Method Rate field based on the selected option
         $('#contingency_method').change(function() {
             var selectedOption = $(this).val();
             if (selectedOption == 'sliding_scale') {
                 $('#sliding-scale').show();
                 $('#sliding-btn').show();

             } else {
                 $('#sliding-scale').hide();
                 $('#sliding-btn').hide();

             }
         });
     });

     document.addEventListener('DOMContentLoaded', function() {
         const container = document.querySelector('.allocation-container');
         const addRowButton = document.querySelector('.add-row-btn');

         let rowCount = 1; // Track the number of rows added

         function updatePercentage() {
             const total = 100; // Assuming total allocation is 100%
             let percentageText = '';
             let sumOfPercentages = 0;

             const percentageInputs = container.querySelectorAll('.percentage-field');

             percentageInputs.forEach(input => {
                 const enteredPercentage = parseFloat(input.value) || 0;
                 // Limit entered values to 100
                 const limitedPercentage = Math.min(enteredPercentage, 100);
                 input.value = limitedPercentage.toFixed(2);
                 percentageText += `${limitedPercentage.toFixed(2)}%`;

                 sumOfPercentages += limitedPercentage;
             });
         }

         function addRow(event) {
             event.preventDefault(); // Prevent the default form submission behavior

             const newRow = document.createElement('div');
             newRow.classList.add('row', 'allocation-row');

             const percentageDiv = document.createElement('div');
             percentageDiv.classList.add('col-md-5');

             const percentageLabel = document.createElement('label');
             percentageLabel.textContent = 'Percentage:';
             percentageDiv.appendChild(percentageLabel);

             const percentageInput = document.createElement('input');
             percentageInput.type = 'number';
             percentageInput.classList.add('percentage-field', 'form-control');
             percentageInput.step = '0.01';
             percentageInput.name = 'sliding_scale[' + rowCount +
                 '][percentage]'; // Assign name attribute dynamically

             percentageInput.addEventListener('input', function() {
                 // Allow digits, dots, and backspace (works with number input type)
                 this.value = this.value.replace(/[^0-9.]/g, '');

                 let enteredValue = this.value.replace(/[^0-9.]/g,
                     ''); // Allow digits, dots, and backspace
                 enteredValue = enteredValue.replace(/^(\d*\.\d{0,2}|\d*)$/,
                     '$1'); // Limit to 2 decimal places

                 const limitedPercentage = Math.min(parseFloat(enteredValue) || 0, 100);
                 this.value = limitedPercentage.toFixed(2);

                 updatePercentage();
             });

             percentageDiv.appendChild(percentageInput);
             newRow.appendChild(percentageDiv);

             const amountDiv = document.createElement('div');
             amountDiv.classList.add('col-md-5');

             const amountLabel = document.createElement('label');
             amountLabel.textContent = 'Amount:';
             amountDiv.appendChild(amountLabel);

             const amountInput = document.createElement('input');
             amountInput.type = 'number';
             amountInput.classList.add('amount-field', 'form-control');
             amountInput.name = 'sliding_scale[' + rowCount + '][amount]';
             amountDiv.appendChild(amountInput);
             newRow.appendChild(amountDiv);

             const removeButtonDiv = document.createElement('div');
             removeButtonDiv.classList.add('col-md-2', 'text-right');

             const removeButton = document.createElement('button');
             removeButton.textContent = 'Remove';
             removeButton.classList.add('btn', 'btn-danger', 'mt-4', 'remove-row-btn');
             removeButton.addEventListener('click', function() {
                 container.removeChild(newRow);
                 updatePercentage();
             });
             removeButtonDiv.appendChild(removeButton);
             newRow.appendChild(removeButtonDiv);

             container.appendChild(newRow);

             rowCount++; // Increment row count
         }

         addRowButton.addEventListener('click', addRow);
     });




     $(document).ready(function() {
         // Initially hide the Method Rate fields
         $('#flat_fee').hide();
         $('#fixed_rate').hide();

         // Show/hide the Method Rate fields based on the selected option
         $('#contingency_method').change(function() {
             var selectedOption = $(this).val();
             if (selectedOption == 'flat_fee') {
                 $('#flat_fee').show();
                 $('#fixed_rate').hide();
             } else if (selectedOption == 'fixed_rate') {
                 $('#flat_fee').hide();
                 $('#fixed_rate').show();
             } else {
                 $('#flat_fee').hide();
                 $('#fixed_rate').hide();
             }
         });

         // Update method_rate input value when it becomes visible
         $('#flat_fee_input, #fixed_rate_input').on('input', function() {
             var lastEnteredValue = $(this).val();
             $('#method_rate_input').val(lastEnteredValue);
         });
     });


     $(document).ready(function() {
         // Initially hide the Method Rate field
         $('#full_amount').hide();

         // Show/hide the Method Rate field based on the selected option
         $('#contingency_method').change(function() {
             var selectedOption = $(this).val();
             if (selectedOption == 'full_amount') {
                 $('#full_amount').show();
             } else {
                 $('#full_amount').hide();
             }
         });
     });


     $(document).ready(function() {
         $('#method_rate_input').on('input', function() {
             // Get the entered value
             var enteredValue = $(this).val();

             // Check if the entered value is a valid number
             if ($.isNumeric(enteredValue)) {
                 // Limit the value to 100
                 var limitedValue = Math.min(enteredValue, 100);

                 // Display the value as a percentage
                 var percentage = limitedValue + "%";
                 $('#percentage_display').text("Percentage: " + percentage);

                 // Update the input field with the limited value
                 $(this).val(limitedValue);
             } else {
                 // Clear the percentage display if the entered value is not a number
                 $('#percentage_display').text("");
             }
         });
     });


     $(document).ready(function() {
         $('#fixed_rate_input').on('input', function() {
             // Get the entered value
             var enteredValue = $(this).val();

             if ($.isNumeric(enteredValue)) {
                 var limitedValue = Math.min(enteredValue, 100);

                 var percentage = limitedValue + "%";
                 $('#percentage_fixed_rate').text("Percentage: " + percentage);

                 $(this).val(limitedValue);
             } else {
                 $('#percentage_fixed_rate').text("");
             }
         });
     })
 </script>
