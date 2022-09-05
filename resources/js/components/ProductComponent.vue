<template>
  <div class="container mt-3">
    <div class="row justify-content-end mb-3">
      <div class="col-4">
        <button class="btn btn-primary" @click="create">
          <i class="fa-solid fa-circle-plus mr-1"></i> Create
        </button>
      </div>
      <div class="col-4">
        <form @submit.prevent="view">
          <div class="input-group">
            <input
              type="text"
              v-model="search"
              placeholder="search"
              class="form-control"
            />
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
            {{ isEditMode ? "Edit" : "Create" }}
          </h4>
          <div class="card-body">
            <form @submit.prevent="isEditMode ? update() : store()">
              <AlertError :form="product" />
              <div class="form-group">
                <label for="">Name</label>
                <input
                  v-model="product.name"
                  type="text"
                  class="form-control"
                />
                <HasError :form="product" field="name" />
              </div>
              <div class="form-group mb-2">
                <label for="">Price</label>
                <input
                  v-model="product.price"
                  type="number"
                  class="form-control"
                />
                <HasError :form="product" field="price" />
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk mr-1"></i>
                {{ isEditMode ? "Update" : "Save" }}
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
            <tr v-for="product in products.data" :key="product.id">
              <td>{{ product.id }}</td>
              <td>{{ product.name }}</td>
              <td>{{ product.price }}</td>
              <td>
                <button class="btn btn-success btn-sm" @click="edit(product)">
                  <i class="fa-solid fa-pen-to-square"></i>edit
                </button>
                <button
                  class="btn btn-danger btn-sm"
                  @click="destroy(product.id)"
                >
                  <i class="fa-solid fa-trash-can"></i>delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :data="products"
          @pagination-change-page="view"
        ></pagination>
      </div>
    </div>
    <loading :active="isLoading" :is-full-page="fullPage" />
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";

import axios from "axios";
import Form from "vform";
import { Button, HasError, AlertError } from "vform/src/components/bootstrap5";
export default {
  components: {
    Button,
    HasError,
    AlertError,
    Loading,
  },
  data() {
    return {
      isEditMode: false,
      products: {},
      search: "",
      product: new Form({
        id: "",
        name: "",
        price: "",
      }),
      isLoading: true,
      fullPage: true,
    };
  },
  methods: {
    view(page = 1) {
      this.$Progress.start();
      this.isLoading = true;
      axios
        .get(`/api/product?page=${page}&search=${this.search}`)
        .then((res) => {
          this.products = res.data;
          this.$Progress.finish();
          this.isLoading = false;
        })
        .catch((err) => {
          this.$Progress.fail();
        });
    },
    store() {
      this.product.post("/api/product", this.product).then((res) => {
        this.view();
        this.product.reset();
        Toast.fire({
          icon: "success",
          title: "Product created successfully",
        });
      });
    },
    edit(product) {
      (this.isEditMode = true),
        this.product.fill(product),
        this.product.clear();
    },
    create() {
      (this.isEditMode = false), this.product.reset(), this.product.clear();
    },
    update() {
      this.product.put(`/api/product/${this.product.id}`).then((res) => {
        this.view();
        this.product.reset();
        Toast.fire({
          icon: "success",
          title: "Product updated successfully",
        });
      });
    },
    destroy(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/api/product/${id}`).then((res) => this.view());
          Swal.fire("Deleted!", "Your file has been deleted.", "success");
        }
      });
    },
  },
  created() {
    this.view();
  },
};
</script>

<style>
</style>