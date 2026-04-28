import React, { useState } from "react";
import { ChevronLeft, ChevronRight, X, ZoomIn } from "lucide-react";

function ImageCarousel({ images }) {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isFullscreenOpen, setIsFullscreenOpen] = useState(false);
  const [fullscreenIndex, setFullscreenIndex] = useState(0);

  if (!images || images.length === 0) return null;

  const goToPrevious = () => {
    setCurrentIndex((prev) =>
      prev === 0 ? images.length - 1 : prev - 1
    );
  };

  const goToNext = () => {
    setCurrentIndex((prev) =>
      prev === images.length - 1 ? 0 : prev + 1
    );
  };

  const goToFullscreenPrevious = () => {
    setFullscreenIndex((prev) =>
      prev === 0 ? images.length - 1 : prev - 1
    );
  };

  const goToFullscreenNext = () => {
    setFullscreenIndex((prev) =>
      prev === images.length - 1 ? 0 : prev + 1
    );
  };

  const openFullscreen = () => {
    setFullscreenIndex(currentIndex);
    setIsFullscreenOpen(true);
  };

  // Gestion des touches clavier
  React.useEffect(() => {
    const handleKeyDown = (e) => {
      if (!isFullscreenOpen) return;
      
      if (e.key === "ArrowLeft") goToFullscreenPrevious();
      if (e.key === "ArrowRight") goToFullscreenNext();
      if (e.key === "Escape") setIsFullscreenOpen(false);
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isFullscreenOpen]);

  return (
    <>
      {/* Carrousel normal */}
      <div className="relative w-full mb-4 rounded-xl overflow-hidden bg-gray-900 group cursor-pointer" onClick={openFullscreen}>
        {/* Image actuelle */}
        <img
          src={`http://127.0.0.1:8000/storage/${images[currentIndex]}`}
          alt={`post-${currentIndex}`}
          className="w-full h-96 object-cover hover:brightness-75 transition-all duration-200"
        />

        {/* Icône zoom au survol */}
        <div className="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-black/20">
          <ZoomIn className="w-10 h-10 text-white" />
        </div>

        {/* Boutons de navigation - visibles au survol */}
        {images.length > 1 && (
          <>
            <button
              onClick={(e) => {
                e.stopPropagation();
                goToPrevious();
              }}
              className="absolute left-3 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10"
            >
              <ChevronLeft className="w-5 h-5" />
            </button>

            <button
              onClick={(e) => {
                e.stopPropagation();
                goToNext();
              }}
              className="absolute right-3 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-black/70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10"
            >
              <ChevronRight className="w-5 h-5" />
            </button>

            {/* Indicateur de position */}
            <div className="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/60 text-white px-3 py-1 rounded-full text-sm font-medium">
              {currentIndex + 1} / {images.length}
            </div>

            {/* Points de navigation */}
            <div className="absolute bottom-3 right-3 flex gap-1">
              {images.map((_, index) => (
                <button
                  key={index}
                  onClick={(e) => {
                    e.stopPropagation();
                    setCurrentIndex(index);
                  }}
                  className={`w-2 h-2 rounded-full transition-all ${
                    index === currentIndex
                      ? "bg-white w-6"
                      : "bg-white/50 hover:bg-white/80"
                  }`}
                />
              ))}
            </div>
          </>
        )}
      </div>

      {/* Modal fullscreen */}
      {isFullscreenOpen && (
        <div className="fixed inset-0 z-50 bg-black/95 flex items-center justify-center">
          {/* Image fullscreen */}
          <div className="relative w-full h-full flex items-center justify-center group">
            <img
              src={`http://127.0.0.1:8000/storage/${images[fullscreenIndex]}`}
              alt={`fullscreen-${fullscreenIndex}`}
              className="max-w-full max-h-full object-contain"
            />

            {/* Bouton fermer */}
            <button
              onClick={() => setIsFullscreenOpen(false)}
              className="absolute top-4 right-4 bg-white/20 hover:bg-white/40 text-white p-2 rounded-lg transition-all z-50"
              aria-label="Fermer"
            >
              <X className="w-6 h-6" />
            </button>

            {/* Navigation boutons */}
            {images.length > 1 && (
              <>
                <button
                  onClick={goToFullscreenPrevious}
                  className="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white p-3 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                  aria-label="Image précédente"
                >
                  <ChevronLeft className="w-6 h-6" />
                </button>

                <button
                  onClick={goToFullscreenNext}
                  className="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 text-white p-3 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                  aria-label="Image suivante"
                >
                  <ChevronRight className="w-6 h-6" />
                </button>

                {/* Indicateur de position */}
                <div className="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 text-white px-4 py-2 rounded-lg text-sm font-medium">
                  {fullscreenIndex + 1} / {images.length}
                </div>

                {/* Points de navigation */}
                <div className="absolute bottom-4 right-4 flex gap-2">
                  {images.map((_, index) => (
                    <button
                      key={index}
                      onClick={() => setFullscreenIndex(index)}
                      className={`w-3 h-3 rounded-full transition-all ${
                        index === fullscreenIndex
                          ? "bg-white w-8"
                          : "bg-white/50 hover:bg-white/80"
                      }`}
                      aria-label={`Aller à l'image ${index + 1}`}
                    />
                  ))}
                </div>
              </>
            )}

            {/* Info au clic */}
            <div className="absolute top-4 left-4 bg-black/60 text-white px-3 py-2 rounded-lg text-sm">
              ⌨️ Flèches pour naviguer, Échap pour fermer
            </div>
          </div>
        </div>
      )}
    </>
  );
}

export default ImageCarousel;
