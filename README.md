# Introduction

In operating systems, process scheduling is a critical component that determines the order in which processes are executed by the CPU. The efficiency of process scheduling algorithms directly impacts system performance, including metrics such as waiting time, turnaround time, and throughput. Two of the most fundamental scheduling algorithms are **First-Come, First-Served (FCFS)** and **Shortest Job First (SJF)**, each with its own advantages, disadvantages, and ideal use cases.

**FCFS**, as the name suggests, is a straightforward scheduling algorithm that executes processes in the order of their arrival. It is one of the simplest and easiest to implement scheduling methods, but it is not always the most efficient, particularly in systems where processes have significantly different burst times.

On the other hand, **SJF** aims to minimize waiting time by selecting the process with the shortest burst time for execution next. This algorithm is more efficient than FCFS in many cases but requires knowledge of process burst times, which may not always be available or accurate in real-world scenarios. Additionally, SJF can lead to the issue of starvation, where longer processes may be indefinitely delayed if shorter ones continue to arrive.

This paper explores both FCFS and SJF algorithms, comparing their performance and discussing their strengths and weaknesses. Through this comparison, we aim to understand which scheduling method is more suitable for different types of operating system environments and workloads.
