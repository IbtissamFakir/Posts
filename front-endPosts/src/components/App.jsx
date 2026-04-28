import React from 'react'
import { Route, Routes } from 'react-router-dom'
import ListePosts from './ListePosts'

function App() {
  return (
    <div>
      <Routes>
        <Route path='/' element={<ListePosts/>}/>
      </Routes>
    </div>
  )
}

export default App
