<template>
    <div>   
            <span class="black" color="black"> Select a card Set </span>
            <b-form-select v-model="cardSet" :options="options.cardSet" label="Choose a card Set"></b-form-select>
            <span class="black" color="black"> Select a card Rarity </span>
            <b-form-select v-model="rarity" :options="options.rarity"></b-form-select>
            <span class="black" color="black"> Select a card Race </span>
            <b-form-select v-model="race" :options="options.race"></b-form-select>
            <button @click.prevent="searchCard()" class="btn btn-primary">Filter<i class="fa fa-search"></i></button>
        <b-modal  id="modal-lg" size="lg" centered v-model="modalShow" :busy=true :hide-footer=true :hide-header=true>
            <b-container>
                <b-row class="mb-1 text-center">
                    <b-col cols="6">
                        <b-img :src="card.img | formatImage" alt="Left image"></b-img>
                    </b-col>
                    <b-col align-self="center">
                        <h1>{{card.name}}</h1>
                        <h2>{{card.flavor}}</h2>
                        <h3>{{card.text | prettyText}}</h3>
                        <ul>
                            <li v-if="card.type">
                                <p class="text-left">Type:
                                 <span> {{card.type}} </span>
                                </p>
                            </li>
                            <li v-if="card.rarity">
                                <p class="text-left">Rarity:
                                 <span> {{card.rarity}} </span>
                                </p>
                            </li>
                            <li v-if="card.cardSet">
                                <p class="text-left">Set:
                                 <span> {{card.cardSet}} </span>
                                </p>
                            </li>
                             <li v-if="card.playerClass">
                                <p class="text-left">Class:
                                 <span> {{card.playerClass}} </span>
                                </p>
                            </li>
                             <li v-if="card.mechanics">
                                <p class="text-left">Mechanics:
                                 <span v-for="mechanic in card.mechanics" v-bind:key="mechanic.name"> {{mechanic.name}} </span>
                                </p>
                            </li>
                        </ul>
                    </b-col>
                </b-row>
            </b-container>
        </b-modal>
        <div v-if="showSearch===true">
            <div class="card-deck"  v-for="n in 3" v-bind:key="n">
                <div class="card border-light mb-3" v-for="(cari,index) in caris" v-if="index <= ((n*10)-1) && index >= ((n*10)-10)" @click="fetchCard(cari.id)" v-bind:key="cari.id">
                    <img class="card-img-top" :src="cari.img | formatImage" alt="Card image cap">
                </div>
            </div>
        </div>
        <div v-if="showSearch===false">
            <div class="card-deck"  v-for="n in 3" v-bind:key="n">
                <div class="card border-light mb-3" v-for="(card,index) in cards" v-if="index <= ((n*10)-1) && index >= ((n*10)-10)" @click="fetchCard(card.id)" v-bind:key="card.id">
                    <img class="card-img-top" :src="card.img | formatImage" alt="Card image cap">
                </div>
            </div>
        </div>
        <nav class="margined" aria-label="Page navigation example" margin-top="1">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCards(pagination.prev_page_url)">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCards(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        data() {
          return {
            cards: [],
            card: {
              id: '',
              name: '',
              playerClass:'',
              cardSet: '',
              health: '',
              attack: '',
              faction: '',
              race: '',
              type: '',
              text: '',
              img: '',
              mechanic: '',
              rarity: '',
              flavor: '',
              cost: ''
            },
            card_id: '',
            pagination: {},
            modalShow: false,
            search: '',
            showSearch: false,
            caris: [],
            cardSet: '',
            rarity: '',
            race: '',
            options: {
                cardSet: [ { value: null, text: 'Please select an Set' },
                    { value: 'Basic', text: 'Basic' },
                    { value: 'Classic', text: 'Classic' },
                    { value: 'Hall of Fame', text: 'Hall of Fame' },
                    { value: 'Naxxramas', text: 'Naxxramas' },
                    { value: 'Globins vs Gnomes', text: 'Globins vs Gnomes' },
                    { value: 'Blackrock Mountain', text: 'Blackrock Mountain' },
                    { value: 'The Grand Tournament', text: 'The Grand Tournament' },
                    { value: 'The League of Explorers', text: 'The League of Explorers' },
                    { value: 'Whispers of the Old Gods', text: 'Whispers of the Old Gods' },
                    { value: 'One Night in Karazhan', text: 'One Night in Karazhan' },
                    { value: 'Mean Streets of Gadgetzan', text: 'Mean Streets of Gadgetzan' },
                    { value: "Journey to Un'Goro", text: "Journey to Un'Goro" },
                    { value: 'Knights of the Frozen Throne', text: 'Knights of the Frozen Throne' },
                    { value: 'Kobolds & Catacombs', text: 'Kobolds & Catacombs' },
                    { value: 'The Witchwood', text: 'The Witchwood' },
                    { value: 'The Boomsday Project', text: 'The Boomsday Project' },
                    { value: "Rastakhan's Rumble", text: "Rastakhan's Rumble" },
                    { value: 'Rise of Shadows', text: 'Rise of Shadows' },
                    { value: 'Saviors of Uldum', text: 'Saviors of Uldum' },
                    { value: 'Descent of Dragons', text: 'Descent of Dragons' },
                    { value: "Galakrond's Awakening", text: "Galakrond's Awakening" },
                    { value: 'Ashes of Outland', text: 'Ashes of Outland' },
                    { value: 'Demon Hunter Initiate', text: 'Demon Hunter Initiate' },
                    { value: 'Wild Event', text: 'Wild Event' },
                    { value: 'Battlegrounds', text: 'Battlegrounds' },
                    { value: 'Tavern Brawl', text: 'Tavern Brawl' },
                    { value: 'Tavern Brawl', text: 'Tavern Brawl' },
                    { value: 'Hero Skins', text: 'Hero Skins' },
                    { value: 'Missions', text: 'Missions' },
                    { value: 'Credits', text: 'Credits' }],
                rarity: [
                    { value: null, text: 'Please select an Rarity' },
                    { value: 'Free', text: 'Free' },
                    { value: 'Common', text: 'Common' },
                    { value: 'Rare', text: 'Rare' },
                    { value: 'Epic', text: 'Epic' },
                    { value: 'Legendary', text: 'Legendary' },
                ],
                race: [
                    { value: null, text: 'Please select an Race' },
                    { value: 'Beast', text: 'Beast' },
                    { value: 'Murloc', text: 'Murloc' },
                    { value: 'Totem', text: 'Totem' },
                    { value: 'Mech', text: 'Mech' },
                    { value: 'Elemental', text: 'Elemental' },
                    { value: 'Pirate', text: 'Pirate' },
                    { value: 'Dragon', text: 'Dragon' },
                    { value: 'All', text: 'All' },
                    { value: 'Orc', text: 'Orc' },
                    { value: 'Demon', text: 'Demon' }],
            },
          }
        },

        created () {
          this.fetchCards();
        },

        filters: {
            prettyText(text){
                if (text != null){
                    return text = text.replace(/<[^>]+>/g, '');
                }   
            },
            formatImage(img){
                if (img != null){
                    return img
                }
                else {
                    return "https://i.ibb.co/g9Z23D9/Screenshot-from-2020-04-26-21-21-06-removebg-preview-1.png"
                }
            }
        },

        methods: {
            searchCard(){
                let query_string = `?rarity=${this.rarity}&race=${this.race}&cardSet=${this.cardSet}`
                fetch('api/cards/search' + query_string )
                    .then(res => res.json())
                    .then(res => {
                        this.caris = res;
                        this.search = '';
                        this.showSearch = true;
                    })
                    .catch(err => {
                        console.log(err);
                    })
            },
            fetchCard(id){
                fetch(`/api/card/${id}`)
                    .then(res => res.json())
                    .then(res => this.card = res)
                this.modalShow = true;
            },
            fetchCards(page_url){
                let vm = this;
                page_url = page_url || '/api/cards'
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                    this.cards = res.data;
                    vm.makePagination(res.meta, res.links);
                    this.showSearch = false;
                    }).catch(err => console.log(err))
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev,
                }
                this.pagination = pagination;
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        computed: {
            firstColumn(){
                return this.cards.slice(0,9)
            },
            secondColumn(){
                return this.cards.slice(10, 19)
            },
            thirdColumn(){
                return this.cards.slice(20,29)
            },
        }
    }
</script>

<style>
.modal-header{
    background-color: black;
    border-bottom: 0 none;
}

.modal-footer {
    background-color: black;
    border-top: 0 none;
}
.modal-body{
    background-color: black;
}

nav.margined {
    margin-top: 10px;
    align-self: center;
}

p {
font-family: "trebuchet MS";
color: #222222; 
font-size: 12pt;
text-align: justify;  
line-height: 20px;  
padding: 5px;
margin-top: 5px;
color: white;
}

span.black {
    color: black;
    margin: 0px;
    display: block;
}

span {
    font-family: "trebuchet MS";
    color: #222222; 
    font-size: 12pt;
    text-align: justify;  
    line-height: 20px;  
    padding: 5px;
    margin-top: 5px;
    color: gold;
}

h1 {
    color: rgb(255, 255, 255);
    margin-bottom: 0px;
    word-break: keep-all;
    line-height: 1.5;
    text-align: left;
    font-size: 22.781px;
    margin-top: 1.15228em;
    line-height: 1.38273em;
    margin-bottom: .23046em;
    font-family: Belwe Bold,Georgia,Times,Times New Roman,serif;
    font-weight: 700;
    -webkit-font-smoothing: antialiased;
    margin: .15em 0;
}

h2 {
    text-align: left;
    font-size: 18px;
    font-style: italic;
    word-break: keep-all;
    overflow-wrap: break-word;
    margin: 5px 0px;
    color: gray;
}

h3 {
    font-size: 18px;
    word-break: keep-all;
    overflow-wrap: break-word;
    margin: 5px 0px;
    text-align: left;
    color: goldenrod;
}

.custom-select {
  display: inline-block;
  width: 50%;
}
</style>