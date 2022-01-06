
interface IGameName {
    name: string;
}

window.addEventListener('load', () => {
    const inputSearch: HTMLInputElement = document.querySelector('[data-games-search]');
    if (inputSearch) {
        const autocomplete = require('autocompleter');
        let arrayGamesName: IGameName[] = [];
        autocomplete({
            minLength: 1,
            input: inputSearch,
            fetch: function(text, update) {
                text = text.toLowerCase();
                fetch('/ajax/game/findAllNames/' + text)
                    .then((response) => {
                        return response.json();
                    })
                    .then((games) => {
                        arrayGamesName = games;
                    })
                    .catch((e) => {
                    })
                ;
                const suggestions = arrayGamesName.filter(n => n.name.toLowerCase().includes(text));
                update(suggestions);
            },
            onSelect: function(item) {
                inputSearch.value = item.name;
                window.location.href = '/game/detail/' + item.slug;
            },
            render: function(item) {
                const itemElement = document.createElement("div");
                itemElement.classList.add('autocomplete-item');
                itemElement.textContent = item.name;
                itemElement.addEventListener('click', ()=> {
                    inputSearch.value = item.name;
                });
                return itemElement;
            }
        });
    }
});