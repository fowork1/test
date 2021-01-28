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
                    <el-input-number v-model.number="elementRow.price" :min="100000"></el-input-number>
                </el-form-item>
                <el-form-item label="Bedrooms" prop="bedrooms">
                    <el-input-number v-model.number="elementRow.bedrooms" :min="1"></el-input-number>
                </el-form-item>
                <el-form-item label="Bathrooms" prop="bathrooms">
                    <el-input-number v-model.number="elementRow.bathrooms" :min="1"></el-input-number>
                </el-form-item>
                <el-form-item label="Storeys" prop="storeys">
                    <el-input-number v-model.number="elementRow.storeys" :min="1"></el-input-number>
                </el-form-item>
                <el-form-item label="Garages" prop="garages">
                    <el-input-number v-model.number="elementRow.garages" :min="0"></el-input-number>
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

                // class for element form
                elementRow: {
                    name: null,
                    price: null,
                    bedrooms: null,
                    bathrooms: null,
                    storeys: null,
                    garages: null,
                },

                // element form rules for validate
                elementRules: {
                    name: [
                        { required: true, message: 'Please input name of realty', trigger: 'blur' },
                        { min: 1, max: 255, message: 'Length should be 1 to 255', trigger: 'blur' }
                    ],
                },

                resData: null,              // data for table
                loading: false,             // loading flag
                isShowElementForm: false,   // show element form flag
                editIndex: null,            // index element on form editing
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
                    this.loading = true;
                    this.$axios.delete(`/api/v1/realty/${row.id}`)
                    .then(resp => {
                        if(resp.data.success){
                            this.resData.splice(index, 1);
                            this.$message({
                                type: 'success',
                                message: resp.data.message
                            });
                        } else {
                            this.showError(resp.data);
                        }
                    })
                    .catch(err => {
                        this.showError(err);
                    })
                    .finally(()=>{
                        this.loading = false;
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

            async onSave(){
                this.$refs['elementRow'].validate((valid) => {
                    if (!valid) return;

                    // Create new row
                    if(this.editIndex === null) {
                        this.loading = true;
                        this.$axios.post(`/api/v1/realty`, this.elementRow)
                        .then(resp => {
                            if(resp.data.success){
                                this.resData.push(resp.data.data);
                                this.$message({
                                    type: 'success',
                                    message: resp.data.message
                                });
                                this.isShowElementForm = false;
                            } else {
                                this.showError(resp.data);
                            }
                        })
                        .catch(err => {
                            this.showError(err);
                        })
                        .finally(()=>{
                            this.loading = false;
                        });
                    } else {
                        // update existing row
                        this.loading = true;
                        this.$axios.put(`/api/v1/realty/${this.elementRow.id}`, this.elementRow)
                        .then(resp => {
                            if(resp.data.success){
                                Object.assign(this.resData[this.editIndex], resp.data.data);
                                this.editIndex = null;
                                this.$message({
                                    type: 'success',
                                    message: resp.data.message
                                });
                                this.isShowElementForm = false;
                            } else {
                                this.showError(resp.data);
                            }
                        })
                        .catch(err => {
                            this.showError(err);
                        })
                        .finally(()=>{
                            this.loading = false;
                        });
                    }
                });
            },

            onCancel(){
                this.editIndex = null;
                this.isShowElementForm = false;
                for(var key in this.elementRow){
                    this.elementRow[key] = null;
                }
                this.$refs['elementRow'].clearValidate();
            },

            elementFormClose(done) {
                this.$confirm('Are you sure to close this dialog?')
                .then(_ => {
                    this.onCancel();
                    done();
                })
                .catch(_ => {});
            },

            showError(err){
                let data = err.response ? err.response.data : err;
                let errors = '';
                if(data.errors){
                    for(var erField in data.errors){
                        errors += "<br>" + erField + " => " + data.errors[erField].join("<br>");
                    }
                }

                this.$notify.error({
                    title: data.message,
                    message: errors,
                    dangerouslyUseHTMLString: true,
                });

                console.error(err, data);
            }
        },

        created(){
            this.onSearch();
        }

    }
</script>
