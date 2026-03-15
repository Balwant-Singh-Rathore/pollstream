document.addEventListener("turbo:load", () => {
    const pollElement = document.getElementById("poll-stream");
    if (!pollElement || !window.Echo) return;
    const pollId = pollElement.dataset.pollId;
    let options = JSON.parse(pollElement.dataset.options);
    window.Echo.channel(`poll.${pollId}`)
        .listen(".vote.cast", (e) => {
            options = options.map(opt => {
                if (opt.id == e.optionId) {
                    opt.votes_count += 1;
                }
                return opt;
            });
            const totalVotes = options.reduce((sum, opt) => sum + opt.votes_count, 0);
            const plusOneElem = document.getElementById("plusOneAnimation");
            const totalVotesElem = document.getElementById("totalVotes");
            if (totalVotesElem) {
                totalVotesElem.textContent = totalVotes + " total votes";
            }
            if (plusOneElem) {
                plusOneElem.classList.remove("vote-plus-animate");
                void plusOneElem.offsetWidth;
                plusOneElem.classList.add("vote-plus-animate");
            }
            options.forEach(opt => {
                const pct = totalVotes === 0
                    ? 0
                    : ((opt.votes_count / totalVotes) * 100).toFixed(2);
                const voteCountElem = document.getElementById(`voteCount-${opt.id}`);
                const progressBar = document.getElementById(`progress-${opt.id}`);
                const percentageElem = document.getElementById(`percentage-${opt.id}`);
                if (voteCountElem) {
                    voteCountElem.textContent = `${opt.votes_count} votes · ${pct}%`;
                }
                if (progressBar) {
                    progressBar.style.width = pct + "%";
                }
                if (percentageElem) {
                    percentageElem.textContent = pct + "%";
                }
            });
        });
});
