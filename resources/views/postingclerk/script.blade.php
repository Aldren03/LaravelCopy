<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const hamBurger = document.querySelector(".text-xl");

        hamBurger.addEventListener("click",() => {
            document.querySelector("#sidebar").classList.toggle("hidden");
            document.querySelector("#sidebar").classList.toggle("flex");
        });
    </script>

<script>
    function toggleDropdown(event, dropdownId) {
        event.preventDefault();
        document.getElementById(dropdownId).classList.toggle('d-none');
    }
</script>

<script>
    document.getElementById('calculate').addEventListener('click', function() {

        let amount = parseFloat(document.getElementById('amount').value) || 0;
        let loanPlanSelect = document.getElementById('lplan');
        let selectedOption = loanPlanSelect.options[loanPlanSelect.selectedIndex];
        let months = parseFloat(selectedOption.getAttribute('data-months')) || 1;
        let monthlyInterestRate = parseFloat(selectedOption.getAttribute('data-interest')) || 0;
        let penaltyRate = parseFloat(selectedOption.getAttribute('data-penalty')) || 0;


        let totalInterest = amount * (monthlyInterestRate / 100) * months;
        let totalAmount = amount + totalInterest;

        let monthlyPayableAmount = totalAmount / months;

        let penaltyAmount = monthlyPayableAmount * (penaltyRate / 100);

      
        let startDate = new Date(); 
        let endDate = new Date(startDate);
        endDate.setMonth(startDate.getMonth() + months);
        let weekdays = 0;
        while (startDate <= endDate) {
            let day = startDate.getDay();
            if (day >= 1 && day <= 5) {
                weekdays++;
            }
            startDate.setDate(startDate.getDate() + 1);
        }
        let dailyPayableAmount = totalAmount / weekdays;


        document.getElementById('tpa').textContent = '₱ ' + totalAmount.toFixed(2);
        document.getElementById('dpa').textContent = '₱ ' + dailyPayableAmount.toFixed(2);
        document.getElementById('pa').textContent = '₱ ' + penaltyAmount.toFixed(2);
    });
</script>
<script>
    
    $(document).ready(function() {
        $('#ledger_id').change(function() {
            var ledgerId = $(this).val();
            if (ledgerId) {
                $.ajax({
                    url: '/get-loan-details/' + ledgerId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#posting_borrower_name').val(data.borrower_name);
                            $('#posting_daily_payment').val(data.daily_payment);
                            $('#posting_penalty').val(data.penalty);
                            $('#posting_pay_amount').val(data.daily_payment);
                            $('#posting_borrower_id').val(data.borrower_id); 
                            

                            $.ajax({
                                url: '/get-loan-schedule-dates/' + ledgerId,
                                type: 'GET',
                                dataType: 'json',
                                success: function(dates) {
                                    $('#posting_payment_date').empty();
                                    $('#posting_payment_date').append('<option value="">Select Date</option>');
                                    
                                    var formatter = new Intl.DateTimeFormat('en-US', {
                                        month: 'long',
                                        day: '2-digit',
                                        year: 'numeric'
                                    });

                                    $.each(dates, function(index, date) {
                                        var formattedDate = formatter.format(new Date(date));
                                        $('#posting_payment_date').append('<option value="' + date + '">' + formattedDate + '</option>');
                                    });
                                },
                                error: function() {
                                    $('#posting_payment_date').empty();
                                    $('#posting_payment_date').append('<option value="">Error loading dates</option>');
                                }
                            });
                        }
                    },
                    error: function() {
                        $('#posting_borrower_name').val('');
                        $('#posting_daily_payment').val('');
                        $('#posting_penalty').val('');
                        $('#posting_pay_amount').val('');
                        $('#posting_borrower_id').val('');
                        $('#posting_payment_date').empty();
                    }
                });
            } else {
                $('#posting_borrower_name').val('');
                $('#posting_daily_payment').val('');
                $('#posting_penalty').val('');
                $('#posting_pay_amount').val('');
                $('#posting_borrower_id').val('');
                $('#posting_payment_date').empty();
            }
        });
    });
</script>
