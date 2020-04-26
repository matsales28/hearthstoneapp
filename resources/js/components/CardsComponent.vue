<template>
    <div>
        <b-modal v-model="modalShow">
            <div class>
                <img :src="card.img" :alt="card.name">
            </div>
            <div class>
                <span class>{{card.name}}</span>
                <h2>{{card.text | prettyText()}}</h2>
                <p>{{card.descricao}}</p>
                <div v-for="mechanic in card.mechanics" v-bind:key="mechanic.name">
                    <p>{{mechanic.name}}</p>
                </div>
            </div>
        </b-modal>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCards(pagination.prev_page_url)">Previous</a></li>
                <li class="page-item"><a class="page-link" href="#">Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchCards(pagination.next_page_url)">Next</a></li>
            </ul>
        </nav>
        
        <div class="card" style="width: 18rem;" v-for="card in cards" v-bind:key="card.id">
            <div>
                <b-card
                    @click="fetchCard(card.id)"
                    :title="card.name"
                    :img-src="card.img"
                    :sub-title="card.type"
                    img-alt=""
                    img-top
                    tag="article"
                    style="max-width: 20rem;"
                    class="mb-2"
                >
                    <b-card-text>
                        {{card.text | prettyText()}}
                    </b-card-text>
                </b-card>
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
              mechanic: ''
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
        }
    }
</script>
