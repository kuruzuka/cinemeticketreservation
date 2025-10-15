

# Cinema Ticket Reservation with Improved Merge Sort Algorithm

> A stable and efficient Cinema Ticket Reservation System developed for CCE105 at the University of Mindanao. This project streamlines the movie booking process and features a custom "Improved Merge Sort with Detection" algorithm to optimize the sorting and display of movie listings for a faster user experience.

-----

## ✨ Key Features

  * **Browse Movies**: A user-friendly interface to view all available movies.
  * **Visual Seat Selection**: An interactive seat map that reflects real-time availability.
  * **Real-Time Booking**: A seamless booking process that instantly confirms reservations and updates seat status for all users to prevent double-booking.
  * **Optimized Sorting**: Implements an "Improved Merge Sort with Detection" algorithm to efficiently sort and display movie lists, enhancing UI responsiveness.
  * **CRUD Operations**: Full administrative control over movies and schedules.

-----

## 🧠 The Algorithm: Improved Merge Sort with Detection

The core of this system's performance is an enhanced version of the Merge Sort algorithm, which was invented by John von Neumann in 1945.

Our **"Improved Merge Sort with Detection"** adds a crucial pre-merge check. Before merging two subarrays, the algorithm first verifies if they are already in the correct sorted order (i.e., the last element of the left array is less than or equal to the first element of the right array).

If the data is already sorted, the merge step is **bypassed entirely**. This simple detection mechanism significantly reduces redundant comparisons and operations, especially on partially sorted datasets, resulting in a faster and more fluid user experience when browsing movies.

-----

## 🛠️ Tech Stack

* **Backend**: Laravel
* **Frontend**: Vue.js
* **Framework Glue**: Inertia.js
* **Styling**: Tailwind CSS
* **Database**: MySQL (or as configured in Laravel)
* **Version Control**: Git & GitHub
* **Diagramming**: Lucidchart

-----

## 📸 Screenshots

### Dashboard

### Movies Management

### Edit Movie

### Schedules Management

### Seat Booking & Ticketing

### Bookings List

-----

## 📊 UML Diagram

The system's architecture and entity relationships were mapped out using a UML diagram.

You can view the full diagram on Lucidchart:
[**View UML Diagram**](https://lucid.app/lucidchart/5539e55c-6aa0-4cc3-9573-5e5c98d9b68c/edit?invitationId=inv_8e99c949-9bd9-4952-a803-36a8888c43c0)
<img width="1060" height="600" alt="Cinema System UML" src="https://github.com/user-attachments/assets/34fe25af-c16c-4577-a808-83dfb38c4221" />


*(It is recommended to add a static image of the UML diagram here in the repository for quick reference.)*

-----

## 🚀 Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

* PHP \>= 8.1
* Composer
* Node.js & NPM
* A database server (e.g., MySQL)

### Installation

1.  **Clone the repo**
```sh
git clone https://github.com/kuruzuka/cinemeticketreservation.git
```
2.  **Navigate to the project directory**
```sh
cd cinemeticketreservation
```
3.  **Install PHP dependencies**
```sh
composer install
```
4.  **Install NPM dependencies**
```sh
npm install
```
5.  **Create a copy of your .env file**
```sh
cp .env.example .env
```
6.  **Generate an app encryption key**
```sh
php artisan key:generate
```
7.  **Configure your `.env` file** with your database credentials.
8.  **Run the database migrations**
```sh
php artisan migrate --seed
```
9.  **Compile front-end assets**
```sh
npm run dev
```
10. **Start the development server**
```sh
php artisan serve
```

-----

## 🤝 Contributors

This project was developed by:

* Melbert Dave T. Balanon
* Aaron Paul A. Betita
* Jerico A. Mondalo
* Glenn Matthew C. Senoc
* Jim Paolo F. Pendon

-----

## 🙏 Acknowledgments

A special thanks to the **University of Mindanao, College of Computing Education**, for their guidance and support throughout this project as a partial fulfillment of the requirements in CCE105.
