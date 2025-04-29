import React from "react";

interface PaginationProps {
    activePage: number;
    totalItemsCount: number;
    itemsCountPerPage: number;
    pageRangeDisplayed: number;
    onChange: (pageNumber: number) => void;
}

const Pagination: React.FC<PaginationProps> = ({
   activePage,
   totalItemsCount,
   itemsCountPerPage,
   pageRangeDisplayed,
   onChange,
}) => {
    const totalPages = Math.ceil(totalItemsCount / itemsCountPerPage);
    const pageNumbers = [];

    const startPage = Math.max(1, activePage - Math.floor(pageRangeDisplayed / 2));
    const endPage = Math.min(totalPages, startPage + pageRangeDisplayed - 1);

    for (let i = startPage; i <= endPage; i++) {
        pageNumbers.push(i);
    }

    return (
        <div className="mt-10 text-center">
            <nav
                className="isolate inline-flex -space-x-px rounded-md shadow-xs"
                aria-label="Pagination"
            >
                <button
                    onClick={() => onChange(activePage - 1)}
                    disabled={activePage === 1}
                    className="btn"
                >
                    <span className="sr-only">Previous</span>
                    <svg
                        className="size-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                        data-slot="icon"
                    >
                        <path
                            fillRule="evenodd"
                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                            clipRule="evenodd"
                        />
                    </svg>
                </button>

                {startPage > 1 && (
                    <>
                        <PageButton page={1} isActive={activePage === 1} onClick={onChange} />
                        {startPage > 2 && <span className="inline-flex items-center px-4 py-2 text-gray-700 ring-1 ring-gray-300 ring-inset">⋯</span>}
                    </>
                )}

                {pageNumbers.map((num, index) => (
                    <PageButton key={index} page={num} isActive={num === activePage} onClick={onChange} />
                ))}

                {endPage < totalPages && (
                    <>
                        {endPage < totalPages - 1 && <span className="inline-flex items-center px-4 py-2 text-gray-700 ring-1 ring-gray-300 ring-inset">⋯</span>}
                        <PageButton page={totalPages} isActive={activePage === totalPages} onClick={onChange} />
                    </>
                )}

                <button
                    onClick={() => onChange(activePage + 1)}
                    disabled={activePage === totalPages}
                    className="btn"
                >
                    <span className="sr-only">Next</span>
                    <svg
                        className="size-5"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                        data-slot="icon"
                    >
                        <path
                            fillRule="evenodd"
                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                            clipRule="evenodd"
                        />
                    </svg>
                </button>
            </nav>
        </div>
    );
};

const PageButton: React.FC<{ key: number; page: number; isActive: boolean; onClick: (page: number) => void }> = ({ page, isActive, onClick }) => (
    <button
        onClick={() => onClick(page)}
        className={`btn ${isActive ? "btn-primary" : ""}`}
        aria-current={isActive ? "page" : undefined}
    >
        {page}
    </button>
);

export default Pagination;
