<template>
    <div>

        <!-- Search form -->
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
                <el-button type="primary" @click="onSearch">Search</el-button>
            </el-form-item>
        </el-form>

        <!-- New|Edit element form -->
        <el-dialog title="Element form" :visible.sync="isShowElementForm" width="30%" :before-close="elementFormClose">
            <el-form v-show="isShowElementForm" :model="elementRow" :rules="elementRules" ref="elementRow" label-width="120px" >
                <el-form-item label="Name" prop="name">
                    <el-input v-model="elementRow.name"></el-input>
                </el-form-item>
                <el-form-item label="Price" prop="price">
                    <el-input v-model.number="elementRow.price"></el-input>
                </el-form-item>
                <el-form-item label="Bedrooms" prop="bedrooms">
                    <el-input v-model.number="elementRow.bedrooms"></el-input>
                </el-form-item>
                <el-form-item label="Bathrooms" prop="bathrooms">
                    <el-input v-model.number="elementRow.bathrooms"></el-input>
                </el-form-item>
                <el-form-item label="Storeys" prop="storeys">
                    <el-input v-model.number="elementRow.storeys"></el-input>
                </el-form-item>
                <el-form-item label="Garages" prop="garages">
                    <el-input v-model.number="elementRow.garages"></el-input>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="onSave('ruleForm')">Save</el-button>
                    <el-button @click="onCancel('ruleForm')">Cancel</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>

        <!-- Data view table -->
        <el-table v-loading="loading" :data="resData" style="width: 100%">
            <el-table-column prop="name" label="Name" width="180"></el-table-column>
            <el-table-column prop="price" label="Price" width="180"></el-table-column>
            <el-table-column prop="bedrooms" label="Bedrooms"></el-table-column>
            <el-table-column prop="bathrooms" label="Bathrooms"></el-table-column>
            <el-table-column prop="storeys" label="Storeys"></el-table-column>
            <el-table-column prop="garages" label="Garages"></el-table-column>
            <el-table-column prop="created_at" label="Write date">
                <template slot-scope="scope">{{scope.row.created_at| dateTimeFormat}}</template>
            </el-table-column>
            <el-table-column align="right">
                <template slot="header" slot-scope="scope">
                    <el-button size="mini" @click="onNew">New</el-button>
                </template>
                <template slot-scope="scope">
                    <el-button size="mini" @click="onEdit(scope.$index, scope.row)">Edit</el-button>
                    <el-button size="mini" type="danger" @click="onDelete(scope.$index, scope.row)">Delete</el-button>
                </template>
            </el-table-column>
        </el-table>


    </div>
</template>

<script>
    export default {
        name: 'search',
        data() {
            var validatorCheckPrice = (rule, value, callback) => {
                console.log("validatorCheckPrice START");
                if (!value) {
                    return callback(new Error('Please input the price'));
                }
                setTimeout(() => {
                    /*if (!Number.isInteger(value)) {
                        callback(new Error('Please input digits'));
                    } else {*/
                        if (value < 100000) {
                            callback(new Error('Price must be greater than 100,000.00$'));
                        } else {
                            callback();
                        }
                    //}
                }, 1000);
            };

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

                elementRow: {
                    name: null,
                    price: null,
                    bedrooms: null,
                    bathrooms: null,
                    storeys: null,
                    garages: null,
                },

                elementRules: {
                    price: [
                        { validator: validatorCheckPrice, trigger: 'red' }
                    ]
                },

                resData: null,
                loading: false,
                isShowElementForm: false,
                editIndex: null,
            }
        },
        methods: {
            onSearch() {
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
            },

            onDelete(index, row) {
                this.$confirm('This will permanently delete the row. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.resData.splice(index, 1);
                    this.$message({
                        type: 'success',
                        message: 'Delete completed'
                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: 'Delete canceled'
                    });
                });
            },

            onEdit(index, row) {
              Object.assign(this.elementRow, this.resData[index]);
              this.editIndex = index;
              this.isShowElementForm = true;
            },

            onNew() {
                for(var key in this.elementRow){
                    this.elementRow[key] = null;
                }
                this.isShowElementForm = true;
            },

            onSave(){
                if(this.editIndex !== null) {
                    Object.assign(this.resData[this.editIndex], this.elementRow);
                    this.editIndex = null;
                } else {
                    this.resData.push(this.elementRow);
                }

                this.isShowElementForm = false;
            },

            onCancel(){
                this.editIndex = null;
                this.isShowElementForm = false;
                for(var key in this.elementRow){
                    this.elementRow[key] = null;
                }
            },

            elementFormClose(done) {
                this.$confirm('Are you sure to close this dialog?')
                .then(_ => {
                    this.onCancel();
                    done();
                })
                .catch(_ => {});
            }
        },

        created(){
            this.onSearch();
        }

    }
</script>
