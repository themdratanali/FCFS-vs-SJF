# Function to calculate average waiting time for processes
def avg_waiting_time(schedule):
    time = 0
    waiting = 0
    schedule.sort(key=lambda x: x[0])  # Sorting by arrival time
    done = []

    while schedule:
        # Processes that are ready at this moment
        ready = [p for p in schedule if p[0] <= time]

        if ready:
            if alg == 'SJF':
                ready.sort(key=lambda x: x[1])  # Sorting by burst time (SJF)
            p = ready[0]
            schedule.remove(p)
            waiting += time - p[0]
            done.append((p[0], p[1], time, time + p[1], time - p[0]))  # Adding data
            time += p[1]  # Updating time
        else:
            time = schedule[0][0]  # Waiting until the next process arrives

    return waiting / len(done), done  # Returning average waiting time and table

# Input
n = int(input("Enter number of processes: "))
procs = [tuple(map(int, input(f"P{i+1} (arrival burst): ").split())) for i in range(n)]

# Run for FCFS
alg = 'FCFS'
fcfs_avg, fcfs_table = avg_waiting_time(procs.copy())

# Run for SJF
alg = 'SJF'
sjf_avg, sjf_table = avg_waiting_time(procs.copy())

# Function to display table
def show(title, table, avg):
    print(f"\n{title}\nArrival Burst Start Finish Waiting")
    for a, b, s, f, w in table:
        print(f"{a:7} {b:5} {s:5} {f:6} {w:7}")
    print(f"Average Waiting Time: {avg:.2f}")

# Displaying both tables
show("FCFS", fcfs_table, fcfs_avg)
show("SJF", sjf_table, sjf_avg)

# Output which algorithm is better
better = "FCFS" if fcfs_avg < sjf_avg else "SJF"
print(f"\nIn this case, {better} is better because its Average Waiting Time is lower.")