<template>
    <div>
        <h2>Posts</h2>
        <flash-message class="myCustomClass"></flash-message>
        <form class="mb-3" @submit.prevent="addPost">
            <div class="form-group">
                <input type="text" class="form-control mb-2" placeholder="Title" v-model="post.title">
                <textarea-autosize class="form-control" placeholder="Body" v-model="post.body" :min-height="30" :max-height="350"></textarea-autosize>
            </div>
            <button type="submit" class="btn btn-outline-primary btn-block">Save</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page}]"><a class="page-link" href="#" @click="fetchPosts(pagination.prev_page)">Previous</a></li>
                <li class="page-item disabled"><a class="page-link" href="#"> {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                <li class="page-item" v-bind:class="[{disabled: !pagination.next_page}]"><a class="page-link" href="#" @click="fetchPosts(pagination.next_page)">Next</a></li>
            </ul>
        </nav>
        <div v-for="post in posts" v-bind:key="post.id" class="card card-body">
            <h3>{{ post.title }}</h3>
            <h3>{{ post.body }}</h3>
            <p>{{ post.updated_at }}</p>
            <hr>
            <button class="btn btn-outline-warning mb-2" @click="editPost(post)">Edit</button>
            <button class="btn btn-outline-danger" @click="deletePost(post.id)">Delete</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            post: {
                'id': '',
                'title': '',
                'body': ''
            },
            post_id: '',
            pagination: {},
            edit: false,
            method: 'POST'
        }
    },

    created() {
        this.fetchPosts();
    },

    methods: {
        fetchPosts(page_url)
        {
            let vm = this;
            page_url = page_url || '/api/posts'
            fetch(page_url)
            .then(res => res.json())
            .then(res => {
                this.posts = res.data;
                vm.makePagination(res.meta, res.links);
            })
            .catch(error => this.flash('Something went wrong!', 'error'));
            setTimeout(() => {
                this.fetchPosts()
            }, 5000);
        },
        makePagination(meta, links)
        {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page: links.next,
                prev_page: links.prev
            }

            this.pagination = pagination;
        },
        deletePost(id)
        {
            if(confirm('Are you sure?')) {
                fetch(`api/post/${id}`, {
                    method: "delete"
                })
                .then(res => res.json())
                .then(data => {
                    alert("Post has been deleted!");
                    this.fetchPosts();
                })
                .catch(error => this.flash('Something went wrong!', 'error'));
            }
        },
        addPost()
        {
            if(this.edit == true)
            {
                this.method = 'PATCH';
                this.flash('The post has been updated!', 'success');
            }
            else
                this.flash('The post has been added!', 'success');
            fetch('api/post', {
                method: this.method,
                body: JSON.stringify(this.post),
                headers: {
                    'content-type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.post.title = '';
                this.post.body = '';
                this.fetchPosts();
            })
            .catch(error => this.flash('Something went wrong!', 'error'));
        },
        editPost(post)
        {
            this.edit = true;
            this.post.id = post.id;
            this.post.post_id = post.id;
            this.post.title = post.title;
            this.post.body = post.body;
        }
    }
};
</script>
