import React from "react";
import { Search } from "lucide-react";

function SearchBar({ searchTerm, onSearchChange }) {
  return (
    <div className="w-full mb-6">
      <div className="relative">
        <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
        <input
          type="text"
          value={searchTerm}
          onChange={(e) => onSearchChange(e.target.value)}
          placeholder="Rechercher un post par titre, contenu ou auteur..."
          className="w-full pl-12 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-gray-800 placeholder-gray-400 shadow-sm hover:border-gray-400 transition-colors"
        />
      </div>
    </div>
  );
}

export default SearchBar;
