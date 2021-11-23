import { SessionStorage } from 'quasar';

export const CURRENT_YEAR_STORAGE_KEY = 'CURRENTYEARINSESSIONSTORAGE';
export const ALL_YEARS_STORAGE_KEY = 'ALLYEARSINSESSIONSTORAGE';
export const ATHLETE_STORAGE_KEY = 'CENTERATHLETEINSESSIONSTORAGE';

export interface AthletesStateInterface {
  athlete: Athlete | null;
  newAthlete: Athlete | null;
  allYearsArray: number[];
  currentYear: number;
  searchedAthletes: Athlete[];
  favourites: Athlete[];
  allRelevantAthletes: Athlete[];
  searchString: string;
}

export interface RequirementCollection {
  description: string;
  requirements: {
    bronze: Requirement;
    silver: Requirement;
    gold: Requirement;
  };
}

export interface Requirement {
  requirement_with_unit: string;
  requirement?: string;
  unit?: string;
  description?: string;
}

export interface PerformanceArray {
  performance: string;
  bronze_highlighted?: boolean;
  silver_highlighted?: boolean;
  gold_highlighted?: boolean;
}

export type Discipline = string;

export enum Category {
  coordination = 'coordination',
  endurance = 'endurance',
  speed = 'speed',
  strength = 'strength',
}

export interface Athlete {
  id: number;
  name: string;
  year: number;
  birthday: string | null;
  gender: string;
  notes: string;
  requirements_tag: string;
  medal_scores: {
    [key in Category]: string;
  };
  sportabzeichen_year_array: {
    lower_year: number;
    upper_year: number;
    tag: string;
    actual_sportabzeichen_age: number;
  };
  needed_requirements: {
    [key in Category]: { [key: Discipline]: RequirementCollection };
  };
  performances: {
    [key in Category]: { [key: Discipline]: PerformanceArray };
  };
  favourite: boolean;
}

export interface YearResource {
  current: number;
  allYears: number[];
}

let currentYear = -1;
let allYearsArray = [] as number[];
let athlete = null as Athlete | null;
// cache year stuff, to get smoother page refresh
try {
  const buf = SessionStorage.getItem<number>(CURRENT_YEAR_STORAGE_KEY);
  if (buf) {
    currentYear = buf;
  }
} catch (e) {}
try {
  const buf = SessionStorage.getItem<number[]>(ALL_YEARS_STORAGE_KEY);
  if (buf) {
    allYearsArray = buf;
  }
} catch (e) {}
try {
  const buf = SessionStorage.getItem<Athlete>(ATHLETE_STORAGE_KEY);
  if (buf && buf != null) {
    athlete = buf;
  }
} catch (e) {}

function state(): AthletesStateInterface {
  return {
    athlete: athlete,
    newAthlete: null,
    allYearsArray: allYearsArray,
    currentYear: currentYear,
    searchedAthletes: [],
    favourites: [],
    allRelevantAthletes: [],
    searchString: '',
  };
}

export default state;
