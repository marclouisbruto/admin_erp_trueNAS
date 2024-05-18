
    const dropdowns = document.querySelectorAll('#leftbar .dropdown');

    dropdowns.forEach(item => {
        const dropdownToggle = item.parentElement.querySelector('a:first-child');
        const iconDown = dropdownToggle.querySelector('.down');
        const iconUp = dropdownToggle.querySelector('.up');

        dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();

            item.classList.toggle('print');
            iconDown.classList.toggle('downn');
            iconUp.classList.toggle('upp');
        });

    });
 
    

        

