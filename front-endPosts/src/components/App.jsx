import React, { useState } from 'react'
import { Route, Routes } from 'react-router-dom'
import ListePosts from './ListePosts'
import NavBar from './NavBar'
// Dans App.js
function App() {
  return (
    // Fond très clair avec des "blobs" de couleur en arrière-plan
    <div className="min-h-screen bg-[#FDFDFF] relative overflow-hidden">
      {/* Taches de couleurs douces (Blobs) */}
      <div className="fixed top-0 left-0 w-full h-full pointer-events-none z-0">
        <div className="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-blue-100/50 blur-[120px]"></div>
        <div className="absolute bottom-[10%] left-[-5%] w-[400px] h-[400px] rounded-full bg-purple-100/40 blur-[100px]"></div>
        <div className="absolute top-[40%] left-[20%] w-[300px] h-[300px] rounded-full bg-pink-100/30 blur-[80px]"></div>
      </div>

      <div className="relative z-10">
        <NavBar />
        <main className="max-w-2xl mx-auto py-12 px-4">
          <Routes>
            <Route path='/' element={<ListePosts />} />
          </Routes>
        </main>
      </div>
    </div>
  )
}
export default App
