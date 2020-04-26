<template>
    <div>
        <b-modal  id="modal-lg" size="lg" centered v-model="modalShow" busy="true" hide-footer="true" hide-header="true">
            <b-container>
                <b-row class="mb-1 text-center">
                    <b-col cols="6">
                        <b-img :src="card.img" alt="Left image"></b-img>
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

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCards(pagination.prev_page_url)">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCards(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>

        <div class="card-deck"  v-for="n in 3" v-bind:key="n">
            <div class="card border-light mb-3" v-for="(card,index) in cards" v-if="index <= ((n*10)-1) && index >= ((n*10)-10)" @click="fetchCard(card.id)" v-bind:key="card.id">
                <img class="card-img-top" :src="card.img" alt="Card image cap">
            </div>
        </div>
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
            }
        },

        methods: {
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

</style>