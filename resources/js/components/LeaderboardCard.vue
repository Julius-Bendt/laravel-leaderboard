<template>
  <div class="row justify-content-center pb-4 pt-3">
    <div class="col-md-8 offset-md-2">
      <div class="card">
        <div class="card-header">Leaderboard #{{ id }}</div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-2 offset-md-2">
              <button type="button" class="btn btn-block btn-warning">
                Update
              </button>
            </div>
            <div class="col-md-2 offset-md-4">
              <button type="button" class="btn btn-block btn-danger">
                Delete
              </button>
            </div>
          </div>
          <table class="table table-dark">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Score</th>
                <th scope="col">ID</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(score, i) in scores" :key="i">
                <th scope="row">{{ i }}</th>
                <td>{{ score.username }}</td>
                <td>{{ score.score }}</td>
                <td>{{ score.id }}</td>
                <td>
                  <button type="button" class="btn btn-block btn-danger">
                    Delete score
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>





<script>
export default {
  props: ["id"],
  data() {
    return {
      scores: [
        {
          id: 0,
          name: "test",
          score: 123,
        },
        {
          id: 1,
          name: "glab",
          score: 123,
        },
        {
          id: 2,
          name: "bukz",
          score: 123,
        },
        {
          id: 3,
          name: "code",
          score: 123,
        },
      ],
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    fetch() {},
  },
};
</script>
