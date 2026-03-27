/* 
   Emirates Final JS - 100% Match
*/

document.addEventListener('DOMContentLoaded', function () {
    const listItems = document.querySelectorAll('.match-regions-list li');
    const displayHeader = document.querySelector('.match-display-header');
    const displayMap = document.querySelector('.match-display-body img');
    const linksLists = document.querySelectorAll('.match-links-ul');

    function updateView(li) {
        const regionId = li.getAttribute('data-region');
        const regionName = li.querySelector('a').innerText;

        // Active List Item
        listItems.forEach(item => item.classList.remove('active'));
        li.classList.add('active');

        // Header
        if (displayHeader) displayHeader.innerText = regionName;

        // Map Image
        if (displayMap) {
            displayMap.style.opacity = '0.5';
            setTimeout(() => {
                const baseUrl = typeof BASE_URL !== 'undefined' ? BASE_URL : '../../';
                displayMap.src = baseUrl + 'images/emara/' + regionId + '.png';
                displayMap.style.opacity = '1';
            }, 100);
        }

        // Links List
        linksLists.forEach(ul => ul.classList.remove('active'));
        const targetList = document.getElementById('match-links-' + regionId);
        if (targetList) targetList.classList.add('active');
    }

    listItems.forEach(item => {
        item.addEventListener('mouseenter', function () {
            updateView(this);
        });

        item.addEventListener('touchstart', function () {
            updateView(this);
        }, { passive: true });
    });

    // Init
    const initial = document.querySelector('.match-regions-list li.active');
    if (initial) updateView(initial);
});
