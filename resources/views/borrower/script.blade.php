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
        // Get input values
        let amount = parseFloat(document.getElementById('amount').value) || 0;
        let loanPlanSelect = document.getElementById('lplan');
        let selectedOption = loanPlanSelect.options[loanPlanSelect.selectedIndex];
        let months = parseFloat(selectedOption.getAttribute('data-months')) || 1;
        let monthlyInterestRate = parseFloat(selectedOption.getAttribute('data-interest')) || 0;
        let penaltyRate = parseFloat(selectedOption.getAttribute('data-penalty')) || 0;

        // Calculate the total amount with fixed monthly interest
        let totalInterest = amount * (monthlyInterestRate / 100) * months;
        let totalAmount = amount + totalInterest;

        // Calculate the monthly payment
        let monthlyPayableAmount = totalAmount / months;

        // Calculate penalty amount
        let penaltyAmount = monthlyPayableAmount * (penaltyRate / 100);

        // Calculate the number of weekdays (Monday to Friday)
        let startDate = new Date(); // Assume starting today
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

        // Update the displayed values
        document.getElementById('tpa').textContent = '₱ ' + totalAmount.toFixed(2);
        document.getElementById('dpa').textContent = '₱ ' + dailyPayableAmount.toFixed(2);
        document.getElementById('pa').textContent = '₱ ' + penaltyAmount.toFixed(2);
    });
</script>

<script>
const addressesByMunicipality = {
    //anao
    Anao: [
        { id: 1, address: 'Baguindoc (Baguinloc)' },
        { id: 2, address: 'Bantog' },
        { id: 3, address: 'Campos' },
        { id: 4, address: 'Carmen' },
        { id: 5, address: 'Casili' },
        { id: 6, address: 'Don Ramon' },
        { id: 7, address: 'Hernando' },
        { id: 8, address: 'Poblacion' },
        { id: 9, address: 'Rizal' },
        { id: 10, address: 'San Francisco East' },
        { id: 11, address: 'San Francisco West' },
        { id: 12, address: 'San Jose North' },
        { id: 13, address: 'San Jose South' },
        { id: 14, address: 'San Juan' },
        { id: 15, address: 'San Roque' },
        { id: 16, address: 'Santo Domingo' },
        { id: 17, address: 'Sinense' },
        { id: 18, address: 'Suaverdez' }
    ],

    //Bamban
    Bamban: [
        { id: 1, address: 'Anupul' },
        { id: 2, address: 'Banaba' },
        { id: 3, address: 'Bangcu' },
        { id: 4, address: 'Culubasa' },
        { id: 5, address: 'Dela Cruz' },
        { id: 6, address: 'La Paz' },
        { id: 7, address: 'Lourdes' },
        { id: 8, address: 'Malonzo' },
        { id: 9, address: 'San Nicolas (Poblacion)' },
        { id: 10, address: 'San Pedro' },
        { id: 11, address: 'San Rafael' },
        { id: 12, address: 'San Roque' },
        { id: 13, address: 'San Vicente' },
        { id: 14, address: 'San Niño' },
        { id: 15, address: 'Virgen de los Remedios (Pacalcal)' }
    ],
    
    //camilling
    Cmilling: [
        { id: 1, address: 'Anoling 1st' },
        { id: 2, address: 'Anoling 2nd' },
        { id: 3, address: 'Anoling 3rd' },
        { id: 4, address: 'Bacabac' },
        { id: 5, address: 'Bacsay' },
        { id: 6, address: 'Bancay 1st' },
        { id: 7, address: 'Bancay 2nd' },
        { id: 8, address: 'Bilad' },
        { id: 9, address: 'Birbira' },
        { id: 10, address: 'Bobon 1st' },
        { id: 11, address: 'Bobon 2nd' },
        { id: 12, address: 'Bobon Caarosipan' },
        { id: 13, address: 'Cabanabaan' },
        { id: 14, address: 'Cacamilingan Norte' },
        { id: 15, address: 'Cacamilingan Sur' },
        { id: 16, address: 'Caniag' },
        { id: 17, address: 'Carael' },
        { id: 18, address: 'Cayaoan' },
        { id: 19, address: 'Cayasan' },
        { id: 20, address: 'Cayasan' },
        { id: 21, address: 'Florida' },
        { id: 22, address: 'Lasong' },
        { id: 23, address: 'Libueg' },
        { id: 24, address: 'Malacampa' },
        { id: 25, address: 'Manakem' },
        { id: 26, address: 'Manupeg' },
        { id: 27, address: 'Marawi' },
        { id: 28, address: 'Matubog' },
        { id: 29, address: 'Nagrambacan' },
        { id: 30, address: 'Nagserialan' },
        { id: 31, address: 'Palimbo-Caarosipan' },
        { id: 32, address: 'Palimbo Proper' },
        { id: 33, address: 'Pao 1st' },
        { id: 34, address: 'Pao 2nd' },
        { id: 35, address: 'Pao 3rd' },
        { id: 36, address: 'Papaac' },
        { id: 37, address: 'Pindangan 1st' },
        { id: 38, address: 'Pindangan 2nd' },
        { id: 39, address: 'Poblacion A' },
        { id: 40, address: 'Poblacion B' },
        { id: 41, address: 'Poblacion C' },
        { id: 42, address: 'Poblacion D' },
        { id: 43, address: 'Poblacion E' },
        { id: 44, address: 'Poblacion F' },
        { id: 45, address: 'Poblacion G' },
        { id: 46, address: 'Poblacion H' },
        { id: 47, address: 'Poblacion I' },
        { id: 48, address: 'Poblacion J' },
        { id: 49, address: 'Santa Maria' },
        { id: 50, address: 'Sawat' },
        { id: 51, address: 'Sinilian 1st' },
        { id: 52, address: 'Sinilian 2nd' },
        { id: 53, address: 'Sinilian 3rd' },
        { id: 54, address: 'Sinilian Cacalibosoan' },
        { id: 55, address: 'Sinulatan 1st' },
        { id: 56, address: 'Sinulatan 2nd' },
        { id: 57, address: 'Surgui 1st' },
        { id: 58, address: 'Surgui 2nd' },
        { id: 59, address: 'Surgui 3rd' },
        { id: 60, address: 'Tambugan' },
        { id: 61, address: 'Telbang' },
        { id: 62, address: 'Tuec' }
    ],
    
    //capas
    Capas: [
        { id: 1, address: 'Aranguren' },
        { id: 2, address: 'Bueno' },
        { id: 3, address: 'Cristo Rey' },
        { id: 4, address: 'Cubcub (Poblacion)' },
        { id: 5, address: 'Cutcut 1st' },
        { id: 6, address: 'Cutcut 2nd' },
        { id: 7, address: 'Dolores 2nd' },
        { id: 8, address: 'Estrada (Calingcuan)' },
        { id: 9, address: 'Lawy' },
        { id: 10, address: 'Manga 1st' },
        { id: 11, address: 'Manlapig 2nd' },
        { id: 12, address: 'Maruglu' },
        { id: 13, address: 'O Donnell' },
        { id: 14, address: 'Santa Juliana' },
        { id: 15, address: 'Santa Lucia' },
        { id: 16, address: 'Santa Rita' },
        { id: 17, address: 'Santo Domingo 1st' },
        { id: 18, address: 'Santo Domingo 2nd' },
        { id: 19, address: 'Santo Rosario' },
        { id: 20, address: 'Talaga' },
    ],
      //concepcion
      Concepcion: [
        { id: 1, address: 'Alfonso' },
        { id: 2, address: 'Balutu' },
        { id: 3, address: 'Cafe' },
        { id: 4, address: 'Calius Gueco' },
        { id: 5, address: 'Caluluan' },
        { id: 6, address: 'Castillo' },
        { id: 7, address: 'Corazon de Jesus' },
        { id: 8, address: 'Culatingan' },
        { id: 9, address: 'Dungan' },
        { id: 10, address: 'Dutung-A-Matas' },
        { id: 11, address: 'Green Village' },
        { id: 12, address: 'Lilibangan' },
        { id: 13, address: 'Mabilog' },
        { id: 14, address: 'Magao' },
        { id: 15, address: 'Malupa' },
        { id: 16, address: 'Minane' },
        { id: 17, address: 'Panalicsian' },
        { id: 18, address: 'Pando' },
        { id: 19, address: 'Parang' },
        { id: 20, address: 'Parulung' },
        { id: 21, address: 'Pitabunan' },
        { id: 22, address: 'San Agustin (Murcia)' },
        { id: 23, address: 'San Antonio' },
        { id: 24, address: 'San Isidro (Almendras)' },
        { id: 25, address: 'San Jose (Poblacion)' },
        { id: 26, address: 'San Juan (Castro)' },
        { id: 27, address: 'San Martin' },
        { id: 28, address: 'San Nicolas (Poblacion)	' },
        { id: 29, address: 'San Nicolas Balas' },
        { id: 30, address: 'San Vicente (Caluis/Cobra)' },
        { id: 31, address: 'Santa Cruz' },
        { id: 32, address: 'Santa Maria' },
        { id: 33, address: 'Santa Monica' },
        { id: 34, address: 'Santa Rita' },
        { id: 35, address: 'Santa Rosa	' },
        { id: 36, address: 'Santiago' },
        { id: 37, address: 'Santo Cristo' },
        { id: 38, address: 'Santo Niño' },
        { id: 39, address: 'Santo Rosario (Magunting)' },
        { id: 40, address: 'Talimunduc Marimla' },
        { id: 41, address: 'Talimunduc San Miguel' },
        { id: 42, address: 'Telabanca' },
        { id: 43, address: 'Tinang' },
       

    ],
     //gerona
     Gerona: [
        { id: 1, address: 'Abagon' },
        { id: 2, address: 'Amacalan' },
        { id: 3, address: 'Apsayan' },
        { id: 4, address: 'Ayson' },
        { id: 5, address: 'Bawa' },
        { id: 6, address: 'Buenlag' },
        { id: 7, address: 'Bularit' },
        { id: 8, address: 'Calayaan' },
        { id: 9, address: 'Carbonel' },
        { id: 10, address: 'Cardona' },
        { id: 11, address: 'Caturay' },
        { id: 12, address: 'Danzo' },
        { id: 13, address: 'Dicolor' },
        { id: 14, address: 'Don Basilio	' },
        { id: 15, address: 'Luna' },
        { id: 16, address: 'Mabini' },
        { id: 17, address: 'Magaspac' },
        { id: 18, address: 'Malayep' },
        { id: 19, address: 'Matapitap' },
        { id: 20, address: 'Matayumcab' },
        { id: 21, address: 'New Salem' },
        { id: 22, address: 'Oloybuaya' },
        { id: 23, address: 'Padapada' },
        { id: 24, address: 'Parsolingan' },
        { id: 25, address: 'Pinasling (Pinasung)' },
        { id: 26, address: 'Plastado' },
        { id: 27, address: 'Poblacion 1' },
        { id: 28, address: 'Poblacion 2' },
        { id: 29, address: 'Poblacion 3' },
        { id: 30, address: 'Quezon' },
        { id: 31, address: 'Rizal' },
        { id: 32, address: 'Salapungan' },
        { id: 33, address: 'San Agustin' },
        { id: 34, address: 'San Antonio' },
        { id: 35, address: 'San Bartolome' },
        { id: 36, address: 'San Jose' },
        { id: 37, address: 'Santiago' },
        { id: 38, address: 'Sembrano' },
        { id: 39, address: 'Singat' },
        { id: 40, address: 'Sulipa' },
        { id: 41, address: 'Tagumbao' },
        { id: 42, address: 'Tangcaran' },
        { id: 43, address: 'Villa Paz' },
       

    ],

    //lapaz
    lapaz: [
        { id: 1, address: 'Balanoy' },
        { id: 2, address: 'Bantog-Caricutan' },
        { id: 3, address: 'Caramutan' },
        { id: 4, address: 'Caut' },
        { id: 5, address: 'Comillas' },
        { id: 6, address: 'Dumarais' },
        { id: 7, address: 'Guevarra' },
        { id: 8, address: 'Kapanikian' },
        { id: 9, address: 'La Purisima' },
        { id: 10, address: 'Lara' },
        { id: 11, address: 'Laungcupang' },
        { id: 12, address: 'Lomboy' },
        { id: 13, address: 'Macalong' },
        { id: 14, address: 'Matayumtayum' },
        { id: 15, address: 'Mayang' },
        { id: 16, address: 'Motrico' },
        { id: 17, address: 'Paludpud' },
        { id: 18, address: 'Rizal' },
        { id: 19, address: 'San Isidro (Poblacion)' },
        { id: 20, address: 'San Roque (Poblacion)' },
        { id: 21, address: 'Sierra' },
        

    ],

      //Mayantoc
      Mayantoc: [
        { id: 1, address: 'Ambalingit' },
        { id: 2, address: 'Baybayaoas' },
        { id: 3, address: 'Bigbiga' },
        { id: 4, address: 'Binbinaca' },
        { id: 5, address: 'Calabtangan' },
        { id: 6, address: 'Caocaoayan' },
        { id: 7, address: 'Carabaoan' },
        { id: 8, address: 'Cubcub' },
        { id: 9, address: 'Gayonggayong' },
        { id: 10, address: 'Gossood' },
        { id: 11, address: 'Labney' },
        { id: 12, address: 'Mamonit' },
        { id: 13, address: 'Maniniog' },
        { id: 14, address: 'Mapandan' },
        { id: 15, address: 'Nambalan' },
        { id: 16, address: 'Pedro L. Quines' },
        { id: 17, address: 'Pitombayog' },
        { id: 18, address: 'Poblacion Norte' },
        { id: 19, address: 'Poblacion Sur' },
        { id: 20, address: 'Rotrottooc' },
        { id: 21, address: 'San Bartolome' },
        { id: 22, address: 'San Jose' },
        { id: 23, address: 'Taldiapan' },
        { id: 24, address: 'Tangcarang' },
       
    ],

    //Moncada
    Moncada: [
        { id: 1, address: 'Ablang-Sapang' },
        { id: 2, address: 'Aringin' },
        { id: 3, address: 'Atencio' },
        { id: 4, address: 'Banaoang East' },
        { id: 5, address: 'Banaoang West' },
        { id: 6, address: 'Baquero Norte' },
        { id: 7, address: 'Baquero Sur' },
        { id: 8, address: 'Burgos' },
        { id: 9, address: 'Calamay' },
        { id: 10, address: 'Calapan' },
        { id: 11, address: 'Camangaan East' },
        { id: 12, address: 'Camangaan West' },
        { id: 13, address: 'Camposanto 1 - Norte' },
        { id: 14, address: 'Camposanto 1 - Sur' },
        { id: 15, address: 'Camposanto 2' },
        { id: 16, address: 'Capaoayan' },
        { id: 17, address: 'Lapsing' },
        { id: 18, address: 'Mabini' },
        { id: 19, address: 'Maluac' },
        { id: 20, address: 'Poblacion 1' },
        { id: 21, address: 'Poblacion 2' },
        { id: 22, address: 'Poblacion 3' },
        { id: 23, address: 'Poblacion 4' },
        { id: 24, address: 'Rizal' },
        { id: 25, address: 'San Juan' },
        { id: 26, address: 'San Julian' },
        { id: 27, address: 'San Leon' },
        { id: 28, address: 'San Pedro' },
        { id: 29, address: 'San Roque' },
        { id: 30, address: 'Santa Lucia East' },
        { id: 31, address: 'Santa Lucia West' },
        { id: 32, address: 'Santa Maria' },
        { id: 33, address: 'Santa Monica' },
        { id: 34, address: 'Tolega Norte' },
        { id: 35, address: 'Tolega Sur' },
        { id: 36, address: 'Tubectubang' },
        { id: 37, address: 'Villa' },
       
    
    ],

    //Paniqui
    Paniqui: [
        { id: 1, address: 'Abogado' },
        { id: 2, address: 'Acocolao' },
        { id: 3, address: 'Aduas' },
        { id: 4, address: 'Apulid' },
        { id: 5, address: 'Balaoang' },
        { id: 6, address: 'Barang (Borang)' },
        { id: 7, address: 'Brillante' },
        { id: 8, address: 'Burgos' },
        { id: 9, address: 'Cabayaoasan' },
        { id: 10, address: 'Canan' },
        { id: 11, address: 'Carino' },
        { id: 12, address: 'Cayanga' },
        { id: 13, address: 'Colibangbang' },
        { id: 14, address: 'Coral' },
        { id: 15, address: 'Dapdap' },
        { id: 16, address: 'Estacion' },
        { id: 17, address: 'Mabilang' },
        { id: 18, address: 'Manaois' },
        { id: 19, address: 'Matalapitap' },
        { id: 20, address: 'Nagmisaan' },
        { id: 21, address: 'Nancamarinan' },
        { id: 22, address: 'Nipaco' },
        { id: 23, address: 'Patalan' },
        { id: 24, address: 'Poblacion Norte	' },
        { id: 25, address: 'Poblacion Sur' },
        { id: 26, address: 'Rang-ayan' },
        { id: 27, address: 'Salumague' },
        { id: 28, address: 'Samput' },
        { id: 29, address: 'San Carlos' },
        { id: 30, address: 'San Isidro' },
        { id: 31, address: 'San Juan de Milla' },
        { id: 32, address: 'Santa Ines' },
        { id: 33, address: 'Sinigpit' },
        { id: 34, address: 'Tablang' },
        { id: 35, address: 'Ventenilla' },
       
    ],
   
     //Pura
     Pura: [
        { id: 1, address: 'Balite' },
        { id: 2, address: 'Buenavista' },
        { id: 3, address: 'Cadanglaan' },
        { id: 4, address: 'Estipona' },
        { id: 5, address: 'Linao' },
        { id: 6, address: 'Maasin' },
        { id: 7, address: 'Matindeg' },
        { id: 8, address: 'Maungib' },
        { id: 9, address: 'Naya' },
        { id: 10, address: 'Nilasin 1st' },
        { id: 11, address: 'Nilasin 2nd' },
        { id: 12, address: 'Poblacion 1' },
        { id: 13, address: 'Poblacion 2' },
        { id: 14, address: 'Poblacion 3' },
        { id: 15, address: 'Poroc' },
        { id: 16, address: 'Singat' },
        
    ],

    //Ramos
    Ramos: [
        { id: 1, address: 'Coral-Iloco' },
        { id: 2, address: 'Guiteb' },
        { id: 3, address: 'Pance' },
        { id: 4, address: 'Poblacion Center' },
        { id: 5, address: 'Poblacion North' },
        { id: 6, address: 'Poblacion South' },
        { id: 7, address: 'San Juan' },
        { id: 8, address: 'San Raymundo' },
        { id: 9, address: 'Toledo' },
   
    ],

    
};

document.getElementById('municipality').addEventListener('change', function() {
    const municipalityId = this.value;
    const municipalityName = this.options[this.selectedIndex].text;
    const homeAddressRow = document.getElementById('home-address-row');
    const addressSelect = document.getElementById('home_address');

    homeAddressRow.classList.add('hidden');
    addressSelect.innerHTML = '<option value="">Select Barangay</option>';

    if (municipalityId) {
        const addresses = addressesByMunicipality[municipalityId] || [];
        addresses.forEach(address => {
            addressSelect.innerHTML += `<option value="${address.address}">${address.address}</option>`;
        });
        homeAddressRow.classList.remove('hidden');

        // Update the home_address input with the selected municipality
        addressSelect.addEventListener('change', function() {
            const barangay = this.value;
            const fullAddress = `${barangay}, ${municipalityName}`;
            this.setAttribute('data-full-address', fullAddress);
        });
    }
});

// Before form submission, set the hidden input field value to the full address
document.querySelector('form').addEventListener('submit', function(event) {
    const homeAddressSelect = document.getElementById('home_address');
    const fullAddress = homeAddressSelect.getAttribute('data-full-address');
    if (fullAddress) {
        homeAddressSelect.value = fullAddress;
    }
});
</script>
