<template>
    <div>

        <el-form :inline="true" :model="searchForm" class="demo-form-inline">
            <el-form-item label="Name">
                <el-input v-model="searchForm.name" placeholder="Name"></el-input>
            </el-form-item>

            <el-form-item label="Price">
                <el-col :span="11">
                    <el-form-item prop="price_from">
                        <el-input-number v-model="searchForm.price_from" :precision="2" :step="10" :min="0"></el-input-number>
                    </el-form-item>
                </el-col>
                <el-col class="line" :span="2">-</el-col>
                <el-col :span="11">
                    <el-form-item prop="price_to">
                        <el-input-number v-model="searchForm.price_to" :precision="2" :step="10" :min="0"></el-input-number>
                    </el-form-item>
                </el-col>
            </el-form-item>

            <el-form-item label="Bedrooms">
                <el-input-number v-model="searchForm.bedrooms" :step="1" :min="0"></el-input-number>
            </el-form-item>

            <el-form-item label="Bathrooms">
                <el-input-number v-model="searchForm.bathrooms" :step="1" :min="0"></el-input-number>
            </el-form-item>

            <el-form-item label="Storeys">
                <el-input-number v-model="searchForm.storeys" :step="1" :min="0"></el-input-number>
            </el-form-item>

            <el-form-item label="Garages">
                <el-input-number v-model="searchForm.garages" :step="1" :min="0"></el-input-number>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="onSubmit">Search</el-button>
            </el-form-item>
        </el-form>


        <el-table v-loading="loading" :data="resData" style="width: 100%">
            <el-table-column prop="name" label="Name" width="180"></el-table-column>
            <el-table-column prop="price" label="Price" width="180"></el-table-column>
            <el-table-column prop="bedrooms" label="Bedrooms"></el-table-column>
            <el-table-column prop="bathrooms" label="Bathrooms"></el-table-column>
            <el-table-column prop="storeys" label="Storeys"></el-table-column>
            <el-table-column prop="garages" label="Garages"></el-table-column>
            <el-table-column prop="created_at" label="Write date"></el-table-column>
        </el-table>


    </div>
</template>

<script>
    export default {
        name: 'search',
        data() {
            return {
                searchForm: {
                    name: null,
                    price_from: null,
                    price_to: null,
                    bedrooms: null,
                    bathrooms: null,
                    storeys: null,
                    garages: null,
                },

                resData: null,

                loading: false
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                let reqData = {};

                // get filters to request
                for(let key in this.searchForm){
                    if(!!this.searchForm[key]){
                        reqData[key] = this.searchForm[key];
                    }
                }

                this.$axios.post('/api/v1/search', reqData)
                .then(resp => {
                    this.resData = resp.data.data;
                    if(this.resData.length == 0){
                        this.$notify({
                            title: 'Nothing found',
                            message: 'No matching objects could be found!',
                            type: 'warning'
                        });
                    }
                })
                .catch(err => {
                    console.error(err.message, err);
                })
                .finally(()=>{
                    this.loading = false;
                })
            }
        },

    }
</script>
