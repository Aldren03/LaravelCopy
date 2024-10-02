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
    function printForm() {
        window.print();
    }
</script>

