<template>
  <div>
    <div class="row justify-content-center">
      <div class="col-md-8 offset-md-2 pb-5 text-center">
        <p>Leaderboard system for Unity</p>
      </div>
      <div class="col-md-8 offset-md-2">
        <button type="button" class="btn btn-block btn-success">
          Create new leaderboard
        </button>
      </div>
    </div>
    <leaderboard-card
      v-for="(leaderboard, i) in leaderboards"
      :key="i"
      v-bind:id="i"
    />
  </div>
</template>

<script>
import LeaderboardCard from "./LeaderboardCard.vue";
export default {
  components: { LeaderboardCard },
  data() {
    return {
      leaderboards: [1, 2, 3, 4, 5],
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
