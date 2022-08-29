<template>
  <div class="container mt-3">
    <div class="row justify-content-end mb-3">
      <div class="col-4">
        <button class="btn btn-primary" @click="create">
          <i class="fa-solid fa-circle-plus mr-1"></i> Create
        </button>
      </div>
      <div class="col-4">
        <form>
          <div class="input-group">
            <input type="text" placeholder="search" class="form-control" />
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-4">
        <div class="card">
          <h4 class="card-header">
            {{isEditMode ? 'Edit': 'Create'}}
          </h4>
          <div class="card-body">
            <form @submit.prevent="isEditMode? update() : store()">
              <div class="form-group">
                <label for="">Name</label>
                <input v-model="product.name" type="text" class="form-control" />
              </div>
              <div class="form-group mb-2">
                <label for="">Price</label>
                <input v-model="product.price" type="number" class="form-control" />
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk mr-1"></i> {{isEditMode ? 'Update':'Save'}}
              </button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-8">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.price }}</td>
              <td>
                <button class="btn btn-success btn-sm" @click="edit(product)">
                  <i class="fa-solid fa-pen-to-square"></i>edit
                </button>
                <button class="btn btn-danger btn-sm" @click="destroy(product.id)">
                  <i class="fa-solid fa-trash-can"></i>delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      isEditMode :false,
      products: [],
      product:
        {
          id:'',
          name:'',
          price:''
        }
      
    };
  },
  methods: {
    view() {
      axios
        .get("/api/product")
        .then((res) => {
          this.products = res.data;
        })
        .catch((err) => {
          console.log(err.message);
        });
    },
    store(){
      axios.post("/api/product",this.product)
      .then(res=>{
        this.view();
        this.product.name = '',
        this.product.price = ''
      })
    },
    edit(product){
      this.isEditMode = true,
      this.product.id = product.id,
      this.product.name = product.name,
      this.product.price = product.price
    },
    create(){
      this.isEditMode = false,
      this.product.id = '',
      this.product.name ='',
      this.product.price = ''
    },
    update(){
      axios.put(`/api/product/${this.product.id}`,this.product)
      .then(res=>{
        this.view();
        this.product.id = '',
        this.product.name = '',
        this.product.price =''
      })
    },
    destroy(id){
      if(!confirm('Are you sure you want to delete?')){
        return
      }
      axios.delete(`/api/product/${id}`)
      .then(res=>
        this.view());
      
    }
  },
  created() {
    this.view();
  },
};
</script>

<style>
</style>